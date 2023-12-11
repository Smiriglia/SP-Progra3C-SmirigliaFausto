<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    class AjusteReserva
    {
        public $id;
        public $idReserva;
        public $motivo;

        public function SetIdReserva($idReserva)
        {
            $ajuste = Reserva::TraerUnaReserva($idReserva);
            if (isset($ajuste))
            {
                $this->idReserva = $idReserva;
                return true;
            }
            return false;
        }

        public function Insertar()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ajustes (idReserva, motivo) VALUES (:idReserva, :motivo)");

            $consulta->bindValue(':idReserva', $this->idReserva, PDO::PARAM_STR);
            $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
            $consulta->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }
        

        public static function TraerTodo()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ajustes");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ajuste');
        }

        public static function TraerUnaReserva($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ajustes WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetchObject('Ajuste');
            if ($resultado === false)
                return null;

            return $resultado;
        }
    }
?>