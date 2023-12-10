<?php

    require_once("./models/class_ajuste_reserva.php");

    class AjusteController {
        public static function InsertarAjuste($reserva, $motivo)
        {
            $ajuste = new AjusteReserva();
            $ajuste->motivo = $motivo;
            if ($ajuste->SetIdReserva($reserva->id))
            {
                $ajuste->Insertar();
                return true;
            }
            return false;
        }
    }

?>