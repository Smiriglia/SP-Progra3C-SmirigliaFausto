<?php
    class AutenticadorUsuario{


        public static function VerificarParametrosCrearCliente($request, $handler)
        {
            $params = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();
            $parametrosRequeridos = ["nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
            if (!isset($archivos['fotoPerfil']))
                throw new Exception('Falta subir una foto de perfil');

            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                {
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
                }
            }
            return $handler->handle($request);
        }

        public static function VerificarParametrosConsultarCliente($request, $handler)
        {
            $params = $request->getParsedBody();
            if (!isset($params["nro_cliente"]))
                throw new Exception('Falta el parametro ' . "nro_cliente");
            if (!isset($params["tipoCliente"]))
                throw new Exception('Falta el parametro ' . "tipoCliente");
            return $handler->handle($request);
        }

        function EstaModificandoCliente()
        {
            $input_data = file_get_contents('php://input');
            $decoded_data = json_decode($input_data, true);
            
            $parametros = ["nro_cliente", "nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
            foreach ($parametros as $parametro)
            {
                if (!isset($decoded_data[$parametro]))
                {
                    echo "<br><br>" . $parametro . "<br><br>";
                    return false;
                }
            }
            return true;
        }

        public static function VerificarParametrosModificarCliente($request, $handler)
        {
            $params = $request->getParsedBody();

            $parametrosRequeridos = ["nro_cliente", "nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
            
            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                {
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
                }
            }
            return $handler->handle($request);
        }

        public static function VerificarParametrosEliminarCliente($request, $handler)
        {
            $queryParams = $request->getQueryParams();
            if (!isset($queryParams["nro_cliente"]))
            {
                throw new Exception('Falta el parametro ' . "nro_cliente");
            }

            if (!isset($queryParams["tipoCliente"]))
            {
                throw new Exception('Falta el parametro ' . "tipoCliente");
            }
            return $handler->handle($request);
        }
    }
?>