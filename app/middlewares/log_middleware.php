<?php
    require_once './controllers/log_accesos_controller.php';
    require_once './controllers/log_transacciones_controller.php';
    require_once './middlewares/autenticador_usuarios.php';
    require_once './middlewares/AutentificadorJWT.php';
    class LogMiddleware
    {
        public static function LogAcceso($request, $handler)
        {
            $metodo = $request->getMethod();
            $accion = $request->getUri()->getPath();

            LogAccesosController::InsertarLogAcceso($metodo, $accion);

            return $handler->handle($request);
        }

        public static function LogTransaccion($request, $handler)
        {
                $coockies = $request->getCookieParams();
                if (isset($coockies['JWT']))
                {
                    $token = $coockies['JWT'];
                    AutentificadorJWT::VerificarToken($token);
                    $datos = AutentificadorJWT::ObtenerData($token);
                    $idUsuario = $datos->id;
                }
                else
                    $idUsuario = -1;

            $response = $handler->handle($request);
            

            $contenidoResultado = json_decode($response->getBody());
            if (isset($contenidoResultado->code))
            {    
                $code = $contenidoResultado->code;
                $accion = $request->getUri()->getPath();
                LogTransaccionesController::InsertarLogTransaccion($idUsuario, $accion, $code);
            }


            
            return $response;
        }
    }

?>