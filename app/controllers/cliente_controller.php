<?php

    require_once("./models/class_cliente.php");
    require_once("./models/class_respuesta.php");

    class ClienteController {
        private function SubirFotoPerfil($fotoPerfil, $cliente)
        {
            $nroCliente = $cliente->nro_cliente;
            $tipoCliente = $cliente->tipoCliente;

            $carpeta_archivos = './ImagenesDeClientes/2023/';

            $nombre_archivo = $fotoPerfil->getClientFilename();
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
            $nombre_archivo = $nroCliente . substr($tipoCliente, 0, 2) . "." . $extension;
            $tipo_archivo = $fotoPerfil->getClientMediaType();
            $tamano_archivo = $fotoPerfil->getSize();

            // Ruta destino, carpeta + nombre del archivo que quiero guardar
            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            // Realizamos las validaciones del archivo
            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 1000000))) {
                return ['error' => "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>"];
            } else {
                try {
                    $fotoPerfil->moveTo($ruta_destino);
                    $cliente->nombreArchivo = $nombre_archivo;
                    return ['mensaje' => "El archivo ha sido cargado correctamente."];
                } catch (Exception $e) {
                    return ['error' => "Ocurrió algún error al subir el fichero. No pudo guardarse."];
                }
            }
        }

        public function Insertar($request, $response, $args) {
            $params = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();

            $paqueteRespuesta = new PaqueteRespuesta();

            $nombre = $params["nombre"];
            $tipoDocumento = $params["tipoDocumento"];
            $numeroDocumento = $params["numeroDocumento"];
            $email = $params["email"];
            $tipoCliente = $params["tipoCliente"];
            $pais = $params["pais"];
            $ciudad = $params["ciudad"];
            $telefono = $params["telefono"];

            if (!isset($params["modalidadPago"]))
                $modalidadPago = "Efectivo";
            else
                $modalidadPago = $params["modalidadPago"];

            if (!isset($archivos["fotoPerfil"]))
                $fotoPerfil = null;
            else
                $fotoPerfil = $archivos["fotoPerfil"];


            $cliente = new Cliente();
            $cliente->nombre = $nombre;
            $cliente->email = $email;
            $cliente->pais = $pais;
            $cliente->ciudad = $ciudad;
            $cliente->telefono = $telefono;
            $cliente->modalidadPago = $modalidadPago;
            
            if ($cliente->setTipoCliente($tipoCliente) and $cliente->SetTipoDocumento($tipoDocumento) and $cliente->SetNumeroDocumento($numeroDocumento))
            {
                $cliente->SetNroCliente();
                if ($fotoPerfil != null)
                {
                    $respuestaArchivo = $this->SubirFotoPerfil($fotoPerfil, $cliente);
                    if (isset($respuestaArchivo["error"]))
                    {
                        $paqueteRespuesta->SetError($respuestaArchivo);
                    }
                    elseif ($cliente->Insertar())
                    {
                        $paqueteRespuesta->SetExito(['mensaje' => "Cliente Agregado Correctamente."]);
                    }
                    else
                    {
                        $paqueteRespuesta->SetError(['error' => "Error, Hubo un problema al guardar el usuario"]);
                    }

                }
                elseif ($cliente->Insertar())
                {
                    $paqueteRespuesta->SetExito(['mensaje' => "Cliente Modificado Correctamente."]);
                }
                else
                { 
                    $paqueteRespuesta->SetError(['error' => "Error, Hubo un problema al guardar el usuario"]);
                }
            }
            else
            {
                $paqueteRespuesta->SetError(['error' => "Error con la informacion del cliente"]);
            }
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ConsultarCliente($request, $response, $args)
        {
            $params = $request->getParsedBody();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nroCliente = $params["nro_cliente"];
            $tipoCliente = $params["tipoCliente"];
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);

            $paqueteRespuesta = new PaqueteRespuesta();
            if (isset($cliente))
            {
                if ($cliente->estado != "Eliminado")
                $paqueteRespuesta->SetExito(["mensaje" => $cliente->MostrarDatos()]);
                else
                    $paqueteRespuesta->SetError(["error" => "El cliente que intentas consultar esta eliminado"]);
            }
            else
            {
                $paqueteRespuesta->SetError(["error" => "Cliente inexistente"]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ObtenerReservasActivas($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nroCliente = $queryParams["nro_cliente"];
            $tipoCliente = $queryParams["tipoCliente"];
            
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $paqueteRespuesta->SetExito(["reservas" => Reserva::ObtenerReservasActivasCliente($cliente)]);
                else
                    $paqueteRespuesta->SetError(["error" => "El cliente esta eliminado"]);
            else
                $paqueteRespuesta->SetError(["error" => "El cliente no existe"]);

            
            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ObtenerCancelacionesCliente($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nroCliente = $queryParams["nro_cliente"];
            $tipoCliente = $queryParams["tipoCliente"];
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $reservasCliente = ["cancelaciones" => Reserva::ObtenerReservasCanceladasCliente($cliente)];
                else
                    $reservasCliente = ["error" => "El cliente esta eliminado"];
            else
                $reservasCliente = ["error" => "El cliente no existe"];

            
            if (isset($reservasCliente["error"]))
                $paqueteRespuesta->SetError($reservasCliente);
            else
                $paqueteRespuesta->SetExito($reservasCliente);

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ObtenerReservas($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nroCliente = $queryParams["nro_cliente"];
            $tipoCliente = $queryParams["tipoCliente"];

            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $reservasCliente = Reserva::ObtenerReservas($cliente);
                else
                    $reservasCliente = ["error" => "El cliente esta eliminado"];
            else
                $reservasCliente = ["error" => "El cliente no existe"];

            if (isset($reservasCliente["error"]))
                $paqueteRespuesta->SetError($reservasCliente);
            else
                $paqueteRespuesta->SetExito($reservasCliente);

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        

        public function ObtenerCancelacionesTipoCliente($request, $response, $args)
        {
            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();


            $tipoCliente = $queryParams["tipoCliente"];
            $clienteAux = new Cliente();
            if ($clienteAux->setTipoCliente($tipoCliente))
            {
                $reservasCliente = Reserva::ObtenerReservasCanceladasTipoCliente($clienteAux->tipoCliente);
                $paqueteRespuesta->SetExito(["cancelaciones" => $reservasCliente]);
            }
            else
            {
                $paqueteRespuesta->SetError(["error" => "tipo cliente invalido"]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function EliminarCliente($request, $response, $args)
        {

            $queryParams = $request->getQueryParams();
            $paqueteRespuesta = new PaqueteRespuesta();

            $nroCliente = $queryParams["nro_cliente"];
            $tipoCliente = $queryParams["tipoCliente"];

            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
            {
                if ($cliente->estado != "Eliminado")
                {
                    $nombreImagen = $cliente->nombreArchivo;
                    $pathInicio = "./ImagenesDeClientes/2023/" . $nombreImagen;
                    $pathFinal = "./ImagenesBackupClientes/2023/" . $nombreImagen;
                    if(file_exists($pathInicio) and rename($pathInicio, $pathFinal))
                        if ($cliente->Eliminar())
                            $paqueteRespuesta->SetExito(['mensaje' => "El cliente se ha eliminado correctamente"]);
                        else
                            $paqueteRespuesta->SetError(['error' => "Error al eliminar el cliente"]);

                    else
                        $paqueteRespuesta->SetError(['error' => 'Error, al mover la foto de perfil a backup']);
                }
                else
                {
                    $paqueteRespuesta->SetError(["error" => "Error, el cliente ya ha sido eliminado"]);
                }
            }
            else
            {
                $paqueteRespuesta->SetError(["error" => "Error, credenciales del cliente incorrectas"]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>