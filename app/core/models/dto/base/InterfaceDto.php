<?php

namespace app\core\models\dto\base;

/**
 * Interface InterfaceDto
 * 
 * Define el contrato base para los DTOs (Data Transfer Object) del sistema.
 * Todos los DTO deben poder convertir sus propiedades a un array y proporcionar su identificador.
 */
interface InterfaceDto
{
    /**
     * Devuelve un arreglo asociativo con los campos del DTO.
     *
     * @return array Arreglo con los campos clave => valor del DTO.
     */
    public function toArray(): array;

    /**
     * Obtiene el ID del DTO.
     *
     * @return int ID del DTO.
     */
    public function getId(): int;
}
