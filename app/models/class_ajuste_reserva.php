<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    class AjusteReserva
    {
        public $id;
        public $idReserva;
        public $motivo;

        public function SetIdReserva($idReserva)
        {
            $ajuste = Reserva::TraerUnaReserva($idReserva);
            if (isset($ajuste))
            {
                $this->idReserva = $idReserva;
                return true;
            }
            return false;
        }

        public function Insertar($rutaArchivo = "./datos/ajustes.json")
        {
            $accesoUltimoIdAjustes = new ManejadorArchivos("./datos/ultimo_id_ajustes.json");
            $objetoUltimoIdAjustes = $accesoUltimoIdAjustes->leer();
            
            $ajustes = AjusteReserva::TraerTodo();

            

            $objetoUltimoIdAjustes["id"] += 1;
            $this->id = $objetoUltimoIdAjustes["id"];
            $ajustes[] = $this;

            AjusteReserva::GuardarTodo($ajustes, $rutaArchivo);
            $accesoUltimoIdAjustes->guardar($objetoUltimoIdAjustes);
            
            return $this->id;
        }
        

        public static function TraerTodo($rutaArchivo = "./datos/ajustes.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $ajustesNoParseados = $objetoAccesoDato->leer();
            $ajustes = [];
            foreach ($ajustesNoParseados as $ajusteNoParseado) 
            {
                $ajuste = new AjusteReserva();
                $ajuste->id = $ajusteNoParseado["id"];
                $ajuste->idReserva = $ajusteNoParseado["idReserva"];
                $ajuste->motivo = $ajusteNoParseado["motivo"];
                $ajustes[] = $ajuste;
            }
            return $ajustes;
        }

        public static function GuardarTodo($ajustes, $rutaArchivo = "./datos/ajustes.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $objetoAccesoDato->guardar($ajustes);
        }

        public static function TraerUnaReserva($id)
        {
            $ajustes = AjusteReserva::TraerTodo();
            foreach ($ajustes as $ajuste) 
            {
                if ($ajuste->id == $id)
                {
                    return $ajuste;
                }
            }
        }
    }
?>