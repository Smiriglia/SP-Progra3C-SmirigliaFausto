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

        public function Insertar()
        {
            $this->estado = "Activo";
            return $this->Crear();
        }


        private function Crear()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO reservas (tipoCliente, nro_cliente, fechaEntrada, fechaSalida, tipoHabitacion, importeTotal, modalidadPago, estado, motivoAjuste) VALUES (:tipoCliente, :nro_cliente, :fechaEntrada, :fechaSalida, :tipoHabitacion, :importeTotal, :modalidadPago, :estado, :motivoAjuste)");

            $consulta->bindValue(':tipoCliente', $this->tipoCliente, PDO::PARAM_STR);
            $consulta->bindValue(':nro_cliente', $this->nro_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':fechaEntrada', $this->fechaEntrada, PDO::PARAM_STR);
            $consulta->bindValue(':fechaSalida', $this->fechaSalida, PDO::PARAM_STR);
            $consulta->bindValue(':tipoHabitacion', $this->tipoHabitacion, PDO::PARAM_STR);
            $consulta->bindValue(':importeTotal', $this->importeTotal, PDO::PARAM_INT);
            $consulta->bindValue(':modalidadPago', $this->modalidadPago, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':motivoAjuste', $this->motivoAjuste, PDO::PARAM_STR);

            $consulta->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }

        public function Actualizar()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE reservas SET tipoCliente = :tipoCliente, nro_cliente = :nro_cliente, fechaEntrada = :fechaEntrada, fechaSalida = :fechaSalida, tipoHabitacion = :tipoHabitacion, importeTotal = :importeTotal, modalidadPago = :modalidadPago, estado = :estado, motivoAjuste = :motivoAjuste WHERE id = :id");

            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':tipoCliente', $this->tipoCliente, PDO::PARAM_STR);
            $consulta->bindValue(':nro_cliente', $this->nro_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':fechaEntrada', $this->fechaEntrada, PDO::PARAM_STR);
            $consulta->bindValue(':fechaSalida', $this->fechaSalida, PDO::PARAM_STR);
            $consulta->bindValue(':tipoHabitacion', $this->tipoHabitacion, PDO::PARAM_STR);
            $consulta->bindValue(':importeTotal', $this->importeTotal, PDO::PARAM_INT);
            $consulta->bindValue(':modalidadPago', $this->modalidadPago, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':motivoAjuste', $this->motivoAjuste, PDO::PARAM_STR);

            $consulta->execute();


            return ["mensaje" => "Se ha actualizado correctamente la reserva"];
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
        

        public static function TraerTodo()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
        }

        public static function GuardarTodo($reservas, $rutaArchivo = "./datos/reservas.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $objetoAccesoDato->guardar($reservas);
        }

        public static function TraerUnaReserva($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetchObject('Reserva');
            if ($resultado === false)
                return null;

            return $resultado;
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