<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    require_once("./utilidades/class_pdf.php");
    class LogAcceso
    {
        public $id;
        public $accion;
        public $metodo;
        public $fecha;

        public function Insertar()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();

            $fecha = new DateTime();
            $fechaStr = $fecha->format('d/m/Y H:i:s');
            $this->fecha = $fechaStr;

            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logAccesos (accion, metodo, fecha) VALUES (:accion, :metodo, :fecha)");

            $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
            $consulta->bindValue(':metodo', $this->metodo, PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }
        

        public static function TraerTodo()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logAccesos");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'LogAcceso');
        }

        public static function TraerUnLog($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logAccesos WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetchObject('LogAcceso');
            if ($resultado === false)
                return null;

            return $resultado;
        }

        public static function ExportarPDF($path = "./datos/logAccesos.pdf")
        {
            $pdf = new PDF();
            $pdf->AddPage();
            
            $logAccesos = LogAcceso::TraerTodo();

            // Agregar objetos al PDF
            foreach ($logAccesos as $logAcceso) {
                $pdf->ChapterTitle($logAcceso->accion);
                $pdf->ChapterBody($logAcceso->metodo . " " .  $logAcceso->fecha);
                $pdf->Ln();
            }

            $pdf->Output($path, 'F');
        }
    }

?>