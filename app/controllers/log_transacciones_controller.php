<?php

    require_once("./models/class_log_transaccion.php");

    class LogTransaccionesController {
        public function ExportarCSV($request, $response, $args)
        {
            $paqueteRespuesta = new PaqueteRespuesta();

            try
            {
                LogTransaccion::ExportarCSV();
                $paqueteRespuesta->SetExito(["mensaje" => "Se ha exportado el log a csv exitosamente"]);
            }
            catch (Exception $e)
            {
                $paqueteRespuesta->SetError(["error" => $e->getMessage()]);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function InsertarLogTransaccion($idUsuario, $accion, $code)
        {
            $logTransaccion = new LogTransaccion();
            $logTransaccion->idUsuario = $idUsuario;
            $logTransaccion->code = $code;
            $logTransaccion->accion = $accion;

            $logTransaccion->Insertar();
        }
    }

?>