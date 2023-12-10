<?php
    class ManejadorArchivos {
        private $urlArchivo;

        public function __construct($urlArchivo) {
            $this->urlArchivo = $urlArchivo;
        }

        public function leer() {
            if (file_exists($this->urlArchivo)) {
                $jsonString = file_get_contents($this->urlArchivo);
                return json_decode($jsonString, true);
            } else {
                return [];
            }
        }

        public function guardar($data) {
            $jsonString = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($this->urlArchivo, $jsonString);
        }
    }
?>