<?php

    require_once("./models/class_log_acceso.php");
    require_once("./models/class_respuesta.php");
    

    class LogAccesosController {
        public function ExportarPDF($request, $response, $args)
        {
            $paqueteRespuesta = new PaqueteRespuesta();

            try
            {
                LogAcceso::ExportarPDF();
                $paqueteRespuesta->SetExito(["mensaje" => "Se ha exportado el log a pdf exitosamente"]);
            }
            catch (Exception $e)
            {
                $paqueteRespuesta->SetError(["error" => $e->getMessage()]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        
        public static function InsertarLogAcceso($metodo, $accion)
        {
            $logAcceso = new LogAcceso();
            $logAcceso->metodo = $metodo;
            $logAcceso->accion = $accion;

            $logAcceso->Insertar();
        }
    }

?>