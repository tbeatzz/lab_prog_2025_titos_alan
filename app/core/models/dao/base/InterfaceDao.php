<?php

namespace app\core\models\dao\base;//agrupar clases

interface InterfaceDao{
    /* devuelve un objeto, con los datos del corresponedinte registro en base datos */
    
    public function load(int $id):array;


    /* guarda los datos del objeto pasasdo como parametro, como un nuevo reg en la bd */
    // data arreglo con datos a guardares como un nuevo reg en la bd
    public function save(array $data):void;   

    public function delete(int $id):void;

    public function list(array $filters):array;
    
    /**
     * foundRows
     * 
     * @return int
     */
    public function foundRows():int;

    public function getLastInserId():int;

}