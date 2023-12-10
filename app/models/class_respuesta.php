<?php
class PaqueteRespuesta
{
    public $response;
    public $code;
    public $success;

    public function SetError($respuesta, $code = 400, $success = false)
    {
        $this->response = $respuesta;
        $this->code = $code;
        $this->success = $success;

    }

    public function SetExito($respuesta, $code = 200, $success = true)
    {
        $this->response = $respuesta;
        $this->code = $code;
        $this->success = $success;
    }

    public function GenerarRespuesta()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
?>