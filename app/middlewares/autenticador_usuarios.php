<?php
    class AutenticadorUsuario{

        public static function VerificarUsuario($request, $handler){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if(self::ValidarRolUsuario($datos->rol)){
                return $handler->handle($request);
            }
            else{
                throw new Exception('No autorizado');
            }
        }

        public static function ValidarPermisosDeRol($request, $handler, $rol = false){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if((!$rol && $datos->rol == 'gerente') || $rol && $datos->rol == $rol || $datos->rol == 'socio'){
                return $handler->handle($request);
            }
            throw new Exception('Acceso denegado');
        }
        public static function ValidarPermisosDeRolClienteRecepcionista($request, $handler){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if($datos->rol == 'cliente' || $datos->rol == 'recepcionista'){
                return $handler->handle($request);
            }
            throw new Exception('Acceso denegado');
        }
        
        public static function ValidarCampos($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['nombre']) || isset($parametros['email']) || isset($parametros['clave']) || isset($parametros['rol']) || isset($parametros['estado'])){
                return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }

        public static function ValidarCamposAlta($request, $handler){
            $params = $request->getParsedBody();

            $parametrosRequeridos = ["nombre", "email", "clave", "rol"];
            
            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
            }
            return $handler->handle($request);
        }

        public static function ValidarCamposLogIn($request, $handler){
            $params = $request->getParsedBody();

            $parametrosRequeridos = ["email", "clave"];
            
            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
            }
            return $handler->handle($request);
        }

        public static function ValidarRolUsuario($rol){
            if($rol !== null){
                if(empty($rol) || $rol != 'socio' && $rol != 'bartender' && $rol != 'cocinero' && $rol != 'mozo' && $rol != 'candybar'){
                    return false;
                }
            }
            return true;
        }


        
    }
?>