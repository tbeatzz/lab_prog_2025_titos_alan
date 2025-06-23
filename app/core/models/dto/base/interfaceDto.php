<?php

namespace app\core\models\dto\base;

interface InterfaceDto{

    /**
     * Devuelve un arreglo con todos los campos de la tabla.
     * @return array Arreglo con los campos de la tabla.
     */
    public function toArray(): array;

}