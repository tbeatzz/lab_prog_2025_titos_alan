<?php

namespace app\core\services;

use app\core\models\dao\CategoriaDao;
use app\core\models\dto\base\InterfaceDto;
use app\core\models\dto\CategoriaDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

final class CategoriaService implements InterfaceService{

    public function load(int $id): InterfaceDto{
        $dao = new CategoriaDao(Connection::get());
        //$data validación de que no sea nulo realizada en el DAO
        $data = $dao->load($id);
        return new CategoriaDto($data);
    }

    public function save(InterfaceDto $dto): void{
        $this->validate($dto);
        $data = $dto->toArray();
        //Uso Unset para eliminar variables o indices del arreglo que no me sirvan:
        unset($data["id"]);
        $dao = new CategoriaDao(Connection::get());
        $dao->save($data);
    }
    
    public function update(InterfaceDto $dto): void{
        $this->validate($dto);
        if($dto->getId() <= 0){
            throw new \Exception("<p>El <strong>id</strong> de la categoría es obligatorio para actualizar.</p>");
        }

        $dao = new CategoriaDao(Connection::get());

        //Validación de existencia utilizando el load del dao que ya valida si existe o no
        $dao->load($dto->getId());

        $dao->update($dto->toArray());
    }

    public function delete(InterfaceDto $dto): void{
        if($dto->getId() <= 0){
            throw new \Exception("<p>El <strong>id</strong> de la categoría es obligatorio para eliminar.</p>");
        }

        $dao = new CategoriaDao(Connection::get());
        //Validación de existencia utilizando el load del dao que ya valida si existe o no
        $dao->load($dto->getId());
        $dao->delete($dto->getId());
    }

    public function list(array $filters): array{
        $dao = new CategoriaDao(Connection::get());
        return $dao->list($filters);
    }

    private function validate(CategoriaDto $dto): void{
        if($dto->getNombre() == ""){
            throw new \Exception("<p>El <strong>nombre</strong> de la categoría es obligatorio.</p>");
        }
    }
}