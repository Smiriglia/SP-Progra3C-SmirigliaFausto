<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    require_once ("./capa_datos/AccesoDatos.php");
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

        public function Insertar()
        {
            if (!isset($this->nro_cliente))
                return false;

            $cliente = Cliente::TraerUnCliente($this->nro_cliente, $this->tipoCliente);
            if (isset($cliente))
                $this->Modificar();
            else
                $this->Crear();
            
            return true;
        }

        private function Crear()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO clientes (nro_cliente, nombre, tipoDocumento, numeroDocumento, email, tipoCliente, pais, ciudad, telefono, modalidadPago, estado, nombreArchivo) VALUES (:nro_cliente, :nombre, :tipoDocumento, :numeroDocumento, :email, :tipoCliente, :pais, :ciudad, :telefono, :modalidadPago, :estado, :nombreArchivo)");

            $consulta->bindValue(':nro_cliente', $this->nro_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
            $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_STR);
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':tipoCliente', $this->tipoCliente, PDO::PARAM_STR);
            $consulta->bindValue(':pais', $this->pais, PDO::PARAM_STR);
            $consulta->bindValue(':ciudad', $this->ciudad, PDO::PARAM_STR);
            $consulta->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
            $consulta->bindValue(':modalidadPago', $this->modalidadPago, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':nombreArchivo', $this->nombreArchivo, PDO::PARAM_STR);

            $consulta->execute();
        }

        private function Modificar()
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes SET nombre = :nombre, tipoDocumento = :tipoDocumento, numeroDocumento = :numeroDocumento, email = :email, tipoCliente = :tipoCliente, pais = :pais, ciudad = :ciudad, telefono = :telefono, modalidadPago = :modalidadPago, estado = :estado WHERE nro_cliente = :nro_cliente");
            $consulta->bindValue(':nro_cliente', $this->nro_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
            $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_STR);
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':tipoCliente', $this->tipoCliente, PDO::PARAM_STR);
            $consulta->bindValue(':pais', $this->pais, PDO::PARAM_STR);
            $consulta->bindValue(':ciudad', $this->ciudad, PDO::PARAM_STR);
            $consulta->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
            $consulta->bindValue(':modalidadPago', $this->modalidadPago, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            
            $consulta->execute();
        }

        public function SetNroCliente()
        {
            
            $clientes = Cliente::TraerTodo();
            $accesoUltimoNroCliente = new ManejadorArchivos("./datos/ultimo_nro_cliente.json");
            $objetoUltimoNroCliente = $accesoUltimoNroCliente->leer();

            foreach ($clientes as $cliente)
            {
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


        
        public static function TraerTodo()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT nro_cliente, nombre, tipoDocumento, numeroDocumento, email, tipoCliente, pais, ciudad, telefono, modalidadPago, estado, nombreArchivo FROM clientes");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
        }

        public static function TraerUnCliente($nro_cliente, $tipoCliente)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $clienteAux = new Cliente();
            if ($clienteAux->setTipoCliente($tipoCliente))
                $tipoCliente = $clienteAux->tipoCliente;
            
            $consulta = $objAccesoDatos->prepararConsulta("SELECT nro_cliente, nombre, tipoDocumento, numeroDocumento, email, tipoCliente, pais, ciudad, telefono, modalidadPago, estado, nombreArchivo FROM clientes WHERE nro_cliente = :nro_cliente AND tipoCliente = :tipoCliente");

            
            $consulta->bindValue(':nro_cliente', $nro_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetchObject('Cliente');
            if ($resultado === false)
                return null;

            return $resultado;
        }
        public static function TraerUnClienteNombreTipo($nombreCliente, $tipoCliente)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();

            $clienteAux = new Cliente();
            if ($clienteAux->setTipoCliente($tipoCliente))
                $tipoCliente = $clienteAux->tipoCliente;

            $consulta = $objAccesoDatos->prepararConsulta("SELECT nro_cliente, nombre, tipoDocumento, numeroDocumento, email, tipoCliente, pais, ciudad, telefono, modalidadPago, estado, nombreArchivo FROM clientes WHERE nombreCliente = :nombreCliente AND tipoCliente = :tipoCliente");

            
            $consulta->bindValue(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
            $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
            $consulta->execute();

            if ($consulta->fetchObject('Cliente') === false)
                return null;
            
            return $consulta->fetchObject('Cliente');
        }
    }
?>