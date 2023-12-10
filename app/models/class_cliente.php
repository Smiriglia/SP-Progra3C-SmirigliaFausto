<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    class Cliente
    {
        public $nro_cliente;
        public $nombre;
        public $tipoDocumento;
        public $numeroDocumento;
        public $email;
        public $tipoCliente;
        public $pais;
        public $ciudad;
        public $telefono;
        public $modalidadPago = "efectivo";
        public $estado = "Activo";
        public $nombreArchivo;

        public function setTipoCliente($tipo)
        {
            $permitidos = ["indi", "corpo"];
            $tipo = strtolower($tipo);
            if ($tipo == "individual")
            {
                $tipo = "indi";
            }
            elseif($tipo == "corporativo")
            {
                $tipo = "corpo";
            }
            if (in_array($tipo, $permitidos, true))
            {
                $this->tipoCliente = $tipo;
                return true;
            }
            return false;
        }

        public function SetTipoDocumento($tipoDocumento)
        {
            $permitidos = ["dni", "le", "le", "pasaporte"];
            
            $tipoDocumento = strtolower($tipoDocumento);
            if (in_array($tipoDocumento, $permitidos, true))
            {
                $this->tipoDocumento = $tipoDocumento;
                return true;
            }
            return false;
        }
        public function SetNumeroDocumento($numeroDocumento)
        {
            $clientes = Cliente::TraerTodo();
            foreach ($clientes as $cliente)
            {
                if ($cliente->numeroDocumento == $numeroDocumento and
                    ($cliente->nombre != $this->nombre or
                    $cliente->tipoCliente != $this->tipoCliente))
                {
                    return false;
                }
            }
            $this->numeroDocumento = $numeroDocumento;
            return true;
        }
        public function MostrarDatos()
        {
            return "Pais: " . $this->pais . "  Ciudad: " . $this->ciudad . "  Telefono: " . $this->telefono;
        }

        public function Insertar($rutaArchivo = "./datos/hoteles.json")
        {
            if (!isset($this->nro_cliente))
                return false;

            $clientes = Cliente::TraerTodo($rutaArchivo);
            $flagEncontrado = false;

            for ($i = 0; $i < count($clientes); $i++) {
                if ($clientes[$i]->nro_cliente == $this->nro_cliente)
                {
                    $this->nombreArchivo = $clientes[$i]->nombreArchivo;
                    $clientes[$i] = $this;
                    $flagEncontrado = true;
                    break;
                }
            }

            if (!$flagEncontrado)
            {
                $this->nro_cliente = str_pad($this->nro_cliente, 6, '0', STR_PAD_LEFT);;
                $clientes[] = $this;
            }

            Cliente::GuardarTodo($clientes, $rutaArchivo);
            
            return true;
        }

        public function SetNroCliente()
        {
            
            $clientes = Cliente::TraerTodo();
            $accesoUltimoNroCliente = new ManejadorArchivos("./datos/ultimo_nro_cliente.json");
            $objetoUltimoNroCliente = $accesoUltimoNroCliente->leer();

            foreach ($clientes as $cliente) {
                if ($cliente->nombre == $this->nombre and $cliente->tipoCliente == $this->tipoCliente)
                {
                    $this->nro_cliente = $cliente->nro_cliente;
                    break;
                }
            }
            if (!isset($this->nro_cliente))
            {
                $objetoUltimoNroCliente["nro_cliente"] += 1;
                $this->nro_cliente = str_pad($objetoUltimoNroCliente["nro_cliente"], 6, '0', STR_PAD_LEFT);;
                $clientes[] = $this;
                $accesoUltimoNroCliente->guardar($objetoUltimoNroCliente);
            }

        }

        public function Eliminar()
        {
            $this->estado = "Eliminado";
            return $this->Insertar();
        }

        public static function TraerTodo($rutaArchivo = "./datos/hoteles.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $clientesNoParseados = $objetoAccesoDato->leer();
            $clientes = [];
            foreach ($clientesNoParseados as $clienteNoParseado) 
            {
                $cliente = new Cliente();
                $cliente->nro_cliente = $clienteNoParseado["nro_cliente"];
                $cliente->nombre = $clienteNoParseado["nombre"];
                $cliente->tipoDocumento = $clienteNoParseado["tipoDocumento"];
                $cliente->numeroDocumento = $clienteNoParseado["numeroDocumento"];
                $cliente->email = $clienteNoParseado["email"];
                $cliente->setTipoCliente($clienteNoParseado["tipoCliente"]);
                $cliente->pais = $clienteNoParseado["pais"];
                $cliente->ciudad = $clienteNoParseado["ciudad"];
                $cliente->telefono = $clienteNoParseado["telefono"];
                $cliente->estado = $clienteNoParseado["estado"];
                $cliente->nombreArchivo = $clienteNoParseado["nombreArchivo"];
                $cliente->modalidadPago = $clienteNoParseado["modalidadPago"];
                $clientes[] = $cliente;
            }
            return $clientes;
        }

        public static function GuardarTodo($clientes, $rutaArchivo = "./datos/reservas.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $objetoAccesoDato->guardar($clientes);
        }

        public static function TraerUnCliente($nro_cliente, $tipoCliente)
        {
            $clientes = Cliente::TraerTodo();
            foreach ($clientes as $cliente) 
            {
                $clienteAux = new Cliente();
                $clienteAux->setTipoCliente($tipoCliente);
                if ($cliente->nro_cliente === $nro_cliente and $cliente->tipoCliente == $clienteAux->tipoCliente)
                {
                    return $cliente;
                }
            }
        }

        public static function TraerUnClienteNombreTipo($nombreCliente, $tipoCliente)
        {
            $clientes = Cliente::TraerTodo();
            foreach ($clientes as $cliente) 
            {
                $clienteAux = new Cliente();
                $clienteAux->setTipoCliente($tipoCliente);
                if ($cliente->nombre === $nombreCliente and $cliente->tipoCliente == $clienteAux->tipoCliente)
                {
                    return $cliente;
                }
            }
        }
    }
?>