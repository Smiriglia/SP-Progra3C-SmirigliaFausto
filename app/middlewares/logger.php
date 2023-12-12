<?php
    require_once './models/class_usuario.php';
    require_once './middlewares/AutentificadorJWT.php';
    require_once './models/class_respuesta.php';
    class Logger
    {
        public static function LogOperacion($request, $response, $next)
        {
            $retorno = $next($request, $response);
            return $retorno;
        }

        public static function LimpiarCoockieUsuario($request, $handler){
            setcookie('JWT', '', time()- 3600, '/', 'localhost', false, true);
            return $handler->handle($request);
        }

        public static function Loguear($request, $response, $args){
            $parametros = $request->getParsedBody();
            $paqueteRespuesta = new PaqueteRespuesta();

            $email = $parametros['email'];
            $clave = $parametros['clave'];
            $usuario = Usuario::obtenerUsuarioEmail($email);
            if($usuario !== null && password_verify($clave, $usuario->clave)){
                $token = AutentificadorJWT::CrearToken(array('id' => $usuario->id, 'nombre' => $usuario->nombre, 'email' => $usuario->email, 'rol' => $usuario->rol, 'estado' => $usuario->estado));
                setcookie('JWT', $token, time()+60*60*24*30, '/', 'localhost', false, true);
                $paqueteRespuesta->SetExito(['mensaje' => 'Logueo Exitoso - Usted es: [ '.$usuario->rol.' ]']);
            }
            else{
                $paqueteRespuesta->SetError(['error' => 'Datos Invalidos']);
            }

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function Salir($request, $response, $args){
            $paqueteRespuesta = new PaqueteRespuesta();
            $paqueteRespuesta->SetExito(['mensaje'=>'Sesion Cerrada']);

            $payload = $paqueteRespuesta->GenerarRespuesta();
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function ValidarSesionIniciada($request, $handler){
            $cookie = $request->getCookieParams();
            if(isset($cookie['JWT'])){
                $token = $cookie['JWT'];
                $datos = AutentificadorJWT::ObtenerData($token);
                if($datos->estado == 'Activo'){
                    return $handler->handle($request);
                }
                else{
                    throw new Exception('Usted no es un usuario activo');
                }
            }
            throw new Exception('Debe haber iniciado sesion');
        }
    }

?>
