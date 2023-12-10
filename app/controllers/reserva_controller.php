<?php

    require_once("./models/class_reserva.php");
    require_once("./models/class_cliente.php");

    class ReservaController {        
        private function SubirFotoReserva($fotoReserva, $tipoCliente, $nro_cliente, $id)
        {
            $carpeta_archivos = './ImagenesDeReservas2023/';
            $nombre_archivo = $fotoReserva->getClientFilename();
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
            $nombre_archivo = $tipoCliente . $nro_cliente . $id . "." . $extension;
            $tipo_archivo = $fotoReserva->getClientMediaType();
            $tamano_archivo = $fotoReserva->getSize();

            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 1000000))) 
            {
                return ["error" => "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>"];
            } 
            else 
            {
                try 
                {
                    $fotoReserva->moveTo($ruta_destino);
                    return ["mensaje" => "El archivo ha sido cargado correctamente."];
                } 
                catch (Exception $e) 
                {
                    return ["error" => "Ocurrió algún error al subir el fichero. No pudo guardarse."];
                }
            }
        }
        public function InsertarReserva($request, $response, $args) {
            $params = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();
            $paqueteRespuesta = new PaqueteRespuesta();

            $tipoCliente = $params["tipo_cliente"];
            $nro_cliente = $params["nro_cliente"];
            $fechaEntrada = $params["fechaEntrada"];
            $fechaSalida = $params["fechaSalida"];
            $tipoHabitacion = $params["tipoHabitacion"];
            $importeTotal = $params["importeTotal"];
            $fotoReserva = $archivos["foto_reserva"];
            
            $reserva = new Reserva();
            $clienteAux = Cliente::TraerUnCliente($nro_cliente, $tipoCliente);
            

            if (isset($clienteAux))
            {
                if ($clienteAux->estado !== "Eliminado")
                {
                    $reserva->nro_cliente = $nro_cliente;
                    $reserva->fechaEntrada = $fechaEntrada;
                    $reserva->fechaSalida = $fechaSalida;
                    $reserva->importeTotal = $importeTotal;
                    
                    if ($reserva->setTipoHabitacion($tipoHabitacion) and isset($clienteAux))
                    {
                        $reserva->tipoCliente = $clienteAux->tipoCliente;
                        $reserva->modalidadPago = $clienteAux->modalidadPago;

                        $id = $reserva->Insertar();
                        $respuestaFotoReserva = $this->SubirFotoReserva($fotoReserva, $clienteAux->tipoCliente, $clienteAux->nro_cliente, $id);
                        if (isset($respuestaFotoReserva["error"]))
                            $paqueteRespuesta->SetError($respuestaFotoReserva);
                        else
                            $paqueteRespuesta->SetExito(["mensaje" => "La Reserva ha sido creada correctamente"]);
                    }
                    else
                    {
                        $paqueteRespuesta->SetError(["error" => "El tipo del reserva es invalido"]);
                    }
                }
                else
                {
                    $paqueteRespuesta->SetError(["error" => "Un cliente eliminado no puede crear una reserva"]);
                }
            }
            else
            {
                $paqueteRespuesta->SetError(["error" => "Cliente no encontrado"]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CalcularTotalReservas($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();
            $tipoHabitacion = $queryParams["tipoHabitacion"];
            

            if (isset($queryParams["fecha"]))
                $fecha = $queryParams["fecha"];
            else
            {
                $hoy = new DateTime();
                $ayer = $hoy->modify('-1 day');
                $fecha = $ayer->format('d/m/Y');
            }
            $importeTotal = Reserva::CalcularTotalReservas($tipoHabitacion, $fecha);

            $mensaje =  "El importe total de habitaciones " . $tipoHabitacion . " en la fecha " . $fecha . " Es de: " . $importeTotal;
            
            $paqueteRespuesta->SetExito(["mensaje" => $mensaje]);
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CalcularTotalCancelado($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $tipoCliente = $queryParams["tipoCliente"];
            if (isset($queryParams["fecha"]))
                $fecha = $queryParams["fecha"];
            else
            {
                $hoy = new DateTime();
                $ayer = $hoy->modify('-1 day');
                $fecha = $ayer->format('d/m/Y');
            }

            $reservas = Reserva::TraerTodo();
            $importeCancelado = 0;

            $clienteAux = new Cliente();
            
            if (!$clienteAux->setTipoCliente($tipoCliente))
            {
                $paqueteRespuesta->SetError(["error" => "El tipo de cliente es invalido"]);
            }
            else
            {
                foreach ($reservas as $reserva)
                {
                    if ($reserva->tipoCliente === $clienteAux->tipoCliente and
                        $reserva->fechaEntrada === $fecha and
                        $reserva->estado === "Cancelado")
                    {
                        $importeCancelado += $reserva->importeTotal;
                    }
                }
    
                $mensaje = "El importe total cancelado clientes de tipo: " . $tipoCliente . " en la fecha " . $fecha . " Es de: " . $importeCancelado;
                $paqueteRespuesta->SetExito(["mensaje" => $mensaje]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ListarEntreFechas($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $fechaEntrada = $queryParams["fechaEntrada"];
            $fechaSalida = $queryParams["fechaSalida"];

            $reservas = Reserva::TraerTodo();
            $reservasFiltradas = [];
            $fechaEntradaParseada = DateTime::createFromFormat('d/m/Y', $fechaEntrada);
            $fechaSalidaParseada = DateTime::createFromFormat('d/m/Y', $fechaSalida);
            
            usort($reservas, function($a, $b)
            {
                $fechaA = DateTime::createFromFormat('d/m/Y', $a->fechaEntrada);
                $fechaB = DateTime::createFromFormat('d/m/Y', $b->fechaEntrada);
                
                if ($fechaA === false || $fechaB === false)
                return 0;
            
            return $fechaB->getTimestamp() - $fechaA->getTimestamp();
            });
        
            foreach ($reservas as $reserva) 
            {
                $fecha = DateTime::createFromFormat('d/m/Y', $reserva->fechaEntrada);
                if ($reserva->estado !== "Cancelado" and $fecha >= $fechaEntradaParseada and $fecha <= $fechaSalidaParseada)
                    $reservasFiltradas[] = $reserva;
            }

            $paqueteRespuesta->SetExito(["reservas" => $reservasFiltradas]);
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        public function ListarEntreFechasCancelado($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $fechaEntrada = $queryParams["fechaEntrada"];
            $fechaSalida = $queryParams["fechaSalida"];

            $reservas = Reserva::TraerTodo();
            $reservasFiltradas = [];
            $fechaEntradaParseada = DateTime::createFromFormat('d/m/Y', $fechaEntrada);
            $fechaSalidaParseada = DateTime::createFromFormat('d/m/Y', $fechaSalida);
            
            usort($reservas, function($a, $b)
            {
                $fechaA = DateTime::createFromFormat('d/m/Y', $a->fechaEntrada);
                $fechaB = DateTime::createFromFormat('d/m/Y', $b->fechaEntrada);
                
                if ($fechaA === false || $fechaB === false)
                    return 0;
            
                return $fechaB->getTimestamp() - $fechaA->getTimestamp();
            });
        
            foreach ($reservas as $reserva) 
            {
                $fecha = DateTime::createFromFormat('d/m/Y', $reserva->fechaEntrada);
                if ($reserva->estado === "Cancelado" and $fecha >= $fechaEntradaParseada and $fecha <= $fechaSalidaParseada)
                {    
                    $reservasFiltradas[] = $reserva;
                }
            }

            $paqueteRespuesta->SetExito(["reservas" => $reservasFiltradas]);
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ListarTipoHabitacion($request, $response, $args)
        {
            $paqueteRespuesta = new PaqueteRespuesta();
            $listaTipos = [];
            $tiposHabitacion = ["doble", "individual", "suite"];
            foreach ($tiposHabitacion as $tipoHabitacion) 
            {
                $listaFiltrada = Reserva::ObtenerReservasTipoHabitacion($tipoHabitacion);
                $listaTipos[$tipoHabitacion] = $listaFiltrada;
            }

            $paqueteRespuesta->SetExito($listaTipos);
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ListarModalidadPago($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $modalidadPago = $queryParams["modalidadPago"];

            $reservasFiltradas = Reserva::ListarModalidadPago($modalidadPago);

            $paqueteRespuesta->SetExito(["reservas" => $reservasFiltradas]);
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CancelarReserva($request, $response, $args)
        {
            $params = $request->getParsedBody();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nro_cliente = $params["nro_cliente"];
            $tipoCliente = $params["tipoCliente"];
            $idReserva = $params["idReserva"];

            $cliente = Cliente::TraerUnCliente($nro_cliente, $tipoCliente);
            if (isset($cliente))
            {
                $respuestaCancelar = Reserva::CancelarReserva($cliente->nro_cliente, $cliente->tipoCliente, $idReserva);
                if (isset($respuestaCancelar["error"]))
                    $paqueteRespuesta->SetError($respuestaCancelar);
                else
                    $paqueteRespuesta->SetExito($respuestaCancelar);
            }
            else
            {
                $paqueteRespuesta->SetError(["error" => "Error, Cliente inexistente"]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function AjustarReserva($request, $response, $args)
        {
            $params = $request->getParsedBody();
            $paqueteRespuesta = new PaqueteRespuesta();

            $idReserva = $params["idReserva"];
            $motivoAjuste = $params["motivoAjuste"];

            $reserva = Reserva::TraerUnaReserva($idReserva);
            if (isset($reserva))
            {
                $respuestaAjustar = $reserva->Ajustar($motivoAjuste);
                if (isset($respuestaAjustar["error"]))
                    $paqueteRespuesta->SetError($respuestaAjustar);
                else
                    $paqueteRespuesta->SetExito($respuestaAjustar);
            }
            else
            $paqueteRespuesta->SetError(['error' => 'Error, Reserva inexistente']);

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>