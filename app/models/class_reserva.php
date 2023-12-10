<?php
    require_once ("./controllers\ajuste_controller.php");
    require_once ("./capa_datos/class_manejador_archivos.php");
    class Reserva
    {
        public $id;
        public $tipoCliente;
        public $nro_cliente;
        public $fechaEntrada;
        public $fechaSalida;
        public $tipoHabitacion;
        public $importeTotal;
        public $estado;
        public $motivoAjuste = null;
        public $modalidadPago;

        public function setTipoHabitacion($tipo)
        {
            $tipo = strtolower($tipo);
            if ($tipo === "doble" or $tipo === "individual" or $tipo === "suite")
            {
                $this->tipoHabitacion = $tipo;
                return true;
            }
            return false;
        }

        public function Insertar($rutaArchivo = "./datos/reservas.json")
        {
            $accesoUltimoIdReservas = new ManejadorArchivos("./datos/ultimo_id_reservas.json");
            $objetoUltimoIdReservas = $accesoUltimoIdReservas->leer();
            
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $reservas = $objetoAccesoDato->leer();

            

            $objetoUltimoIdReservas["id"] += 1;
            $this->id = $objetoUltimoIdReservas["id"];
            $this->estado = "Activo";
            $reservas[] = $this;

            $objetoAccesoDato->guardar($reservas);
            $accesoUltimoIdReservas->guardar($objetoUltimoIdReservas);
            
            return $this->id;
        }
        public function Actualizar($rutaArchivo = "./datos/reservas.json")
        {
            
            $respuesta = [];
            $reservas = Reserva::TraerTodo();
            $indiceReserva = -1;

            for ($i = 0; $i < count($reservas); $i++) {
                if ($reservas[$i]->id === $this->id)
                {
                    $indiceReserva = $i;
                    break;
                }
            }
            
            if ($indiceReserva != -1)
            {
                $reservas[$indiceReserva] = $this;
                Reserva::GuardarTodo($reservas);
                $respuesta["mensaje"] = "Se ha actualizado correctamente la reserva";
            }
            else
            {
                $respuesta["error"] = "Error, no se pudo actualizar la reserva";
            }

            return $respuesta;

        }

        public function Cancelar()
        {
            $this->estado = "Cancelado";
            return $this->Actualizar();
        }

        public function Ajustar($motivoAjuste)
        {
            if (AjusteController::InsertarAjuste($this, $motivoAjuste))
            {
                $this->motivoAjuste = $motivoAjuste;
                return $this->Actualizar();
            }
            else
                return ['error' => 'Error, Datos de ajuste no validos'];
        }
        

        public static function TraerTodo($rutaArchivo = "./datos/reservas.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $reservasNoParseados = $objetoAccesoDato->leer();
            $reservas = [];
            foreach ($reservasNoParseados as $reservaNoParseado) 
            {
                $reserva = new Reserva();
                $reserva->id = $reservaNoParseado["id"];
                $reserva->nro_cliente = $reservaNoParseado["nro_cliente"];
                $reserva->fechaEntrada = $reservaNoParseado["fechaEntrada"];
                $reserva->fechaSalida = $reservaNoParseado["fechaSalida"];
                $reserva->setTipoHabitacion($reservaNoParseado["tipoHabitacion"]);
                $reserva->tipoCliente = $reservaNoParseado["tipoCliente"];
                $reserva->importeTotal = $reservaNoParseado["importeTotal"];
                $reserva->estado = $reservaNoParseado["estado"];
                $reserva->motivoAjuste = $reservaNoParseado["motivoAjuste"];
                $reserva->modalidadPago = $reservaNoParseado["modalidadPago"];
                $reservas[] = $reserva;
            }
            return $reservas;
        }

        public static function GuardarTodo($reservas, $rutaArchivo = "./datos/reservas.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $objetoAccesoDato->guardar($reservas);
        }

        public static function TraerUnaReserva($id)
        {
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($reserva->id == $id)
                {
                    return $reserva;
                }
            }
        }

        public static function CalcularTotalReservas($tipoHabitacion, $fecha){
            $importeTotal = 0;
            $reservas = Reserva::TraerTodo();
            $tipoHabitacion = strtolower($tipoHabitacion);
            foreach ($reservas as $reserva) 
            {
                if ($reserva->tipoHabitacion === $tipoHabitacion and
                    $reserva->fechaEntrada == $fecha and
                    $reserva->estado !== "Cancelado")
                {
                    $importeTotal += $reserva->importeTotal;
                }
            }
            return $importeTotal;
        }

        public static function ObtenerReservasActivasCliente($cliente)
        {
            $reservasCliente = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($cliente->nro_cliente == $reserva->nro_cliente and $reserva->estado !== "Cancelado" )
                    $reservasCliente[] = $reserva;
            }
            return $reservasCliente;
        }

        public static function ObtenerReservasCanceladasCliente($cliente)
        {

            $reservasCliente = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($cliente->nro_cliente == $reserva->nro_cliente and $reserva->estado === "Cancelado")
                    $reservasCliente[] = $reserva;
            }
            return $reservasCliente;
        }

        public static function ObtenerReservas($cliente)
        {

            $reservasCliente = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($cliente->nro_cliente == $reserva->nro_cliente)
                    $reservasCliente[] = $reserva;
            }
            return $reservasCliente;
        }
        public static function ListarModalidadPago($modalidadPago)
        {
            $reservasModalidad = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($reserva->modalidadPago === $modalidadPago)
                    $reservasModalidad[] = $reserva;
            }
            return $reservasModalidad;
        }

        public static function ObtenerReservasCanceladasTipoCliente($tipoCliente)
        {
            $reservasCliente = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($reserva->tipoCliente === $tipoCliente and $reserva->estado === "Cancelado")
                    $reservasCliente[] = $reserva;
            }
            return $reservasCliente;
        }

        public static function ObtenerReservasTipoHabitacion($tipoHabitacion)
        {
            $reservas = Reserva::TraerTodo();
            $reservasFiltradas = [];
            foreach ($reservas as $reserva) 
            {
                if ($reserva->tipoHabitacion == $tipoHabitacion)
                {
                    $reservasFiltradas[] = $reserva;
                }
            }
            return $reservasFiltradas;
        }

        public static function CancelarReserva($nro_cliente, $tipoCliente, $idReserva)
        {
            $reserva = Reserva::TraerUnaReserva($idReserva);
            $respuesta = [];

            if (isset($reserva) and
                $reserva->nro_cliente == $nro_cliente and
                $reserva->tipoCliente === $tipoCliente)
            {
                $respuesta = $reserva->Cancelar();
            }
            else
            {
                $respuesta["error"] = "Error, algun dato dato es incorrecto";
            }
            return $respuesta;
        }
    }
?>