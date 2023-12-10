<?php
    class AutenticadorReserva{

        public static function VerificarParametrosCrearReserva($request, $handler)
        {
            $params = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();
            $parametrosRequeridos = ["tipo_cliente", "nro_cliente", "fechaEntrada","fechaSalida","tipoHabitacion", "importeTotal"];
            if (!isset($archivos['foto_reserva']))
                throw new Exception('Falta subir una foto de reserva');

            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                {
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
                }
            }
            return $handler->handle($request);
        }

        public static function VerificarParametroTipoHabitacion($request, $handler)
        {
            $queryParams = $request->getQueryParams();
            if (!isset($queryParams["tipoHabitacion"]))
                throw new Exception("Falta el parametro tipoHabitacion");

            return $handler->handle($request);
        }

        public static function VerificarParametroTipoCliente($request, $handler)
        {
            $queryParams = $request->getQueryParams();
            if (!isset($queryParams["tipoCliente"]))
                throw new Exception("Falta el parametro tipoCliente");

            return $handler->handle($request);
        }

        public static function VerificarParametroModalidadPago($request, $handler)
        {
            $queryParams = $request->getQueryParams();
            if (!isset($queryParams["modalidadPago"]))
                throw new Exception("Falta el parametro modalidadPago");

            return $handler->handle($request);
        }

        public static function VerificarParametrosConsultarUsuario($request, $handler)
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

        public static function VerificarParametrosCancelarReserva($request, $handler)
        {
            $params = $request->getParsedBody();
            $parametrosRequeridos = ["nro_cliente", "tipoCliente", "idReserva"];

            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
            }
            return $handler->handle($request);
        }

        public static function VerificarParametrosAjustarReserva($request, $handler)
        {
            $params = $request->getParsedBody();
            $parametrosRequeridos = ["idReserva", "motivoAjuste"];

            foreach ($parametrosRequeridos as $parametroRequerido)
            {
                if (!isset($params[$parametroRequerido]))
                    throw new Exception('Falta el parametro ' . $parametroRequerido);
            }
            return $handler->handle($request);
        }

        public static function VerificarParametrosConsultarFechas($request, $handler)
        {
            $queryParams = $request->getQueryParams();
            if (!isset($queryParams["fechaEntrada"]))
            {
                throw new Exception('Falta el parametro ' . "fechaEntrada");
            }

            if (!isset($queryParams["fechaSalida"]))
            {
                throw new Exception('Falta el parametro ' . "fechaSalida");
            }
            return $handler->handle($request);
        }
        
    }
?>