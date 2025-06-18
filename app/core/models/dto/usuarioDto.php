<?php

namespace app\core\model\dto;

use app\core\model\dto\base\InterfaceDto;

final class UserDto implements InterfaceDto {

    private $id, $apellido, $nombres, $cuenta, $perfil, $clave, $correo, $estado, $fechaAlta, $resetPass;

    public function __construct(array $data = []) {
        $this->setId($data["id"] ?? 0);
        $this->setApellido($data["apellido"] ?? "");
        $this->setNombres($data["nombres"] ?? "");
        $this->setCuenta($data["cuenta"] ?? "");
        $this->setPerfil($data["perfil"] ?? "");
        $this->setClave($data["clave"] ?? "");
        $this->setCorreo($data["correo"] ?? "");
        $this->setEstado($data["estado"] ?? "");
        $this->setFechaAlta($data["fechaAlta"] ?? null);
        $this->setResetPass($data["resetPass"] ?? false);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function getNombres(): string {
        return $this->nombres;
    }

    public function getCuenta(): string {
        return $this->cuenta;
    }

    public function getPerfil(): string {
        return $this->perfil;
    }

    public function getClave(): string {
        return $this->clave;
    }

    public function getCorreo(): string {
        return $this->correo;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getFechaAlta(): ?string {
        return $this->fechaAlta;
    }

    public function getResetPass(): bool {
        return $this->resetPass;
    }

    public function setId(int $id): void {
        $this->id = $id > 0 ? $id : 0;
    }

    public function setApellido(string $apellido): void {
        $this->apellido = $apellido;
    }

    public function setNombres(string $nombres): void {
        $this->nombres = $nombres;
    }

    public function setCuenta(string $cuenta): void {
        $this->cuenta = $cuenta;
    }

    public function setPerfil(string $perfil): void {
        $this->perfil = $perfil;
    }

    public function setClave(string $clave): void {
        $this->clave = $clave;
    }

    public function setCorreo(string $correo): void {
        $this->correo = $correo;
    }

    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }

    public function setFechaAlta(?string $fechaAlta): void {
        $this->fechaAlta = $fechaAlta;
    }

    public function setResetPass(bool $resetPass): void {
        $this->resetPass = $resetPass;
    }

    public function toArray(): array {
        return [
            "id"         => $this->getId(),
            "apellido"   => $this->getApellido(),
            "nombres"    => $this->getNombres(),
            "cuenta"     => $this->getCuenta(),
            "perfil"     => $this->getPerfil(),
            "clave"      => $this->getClave(),
            "correo"     => $this->getCorreo(),
            "estado"     => $this->getEstado(),
            "fechaAlta"  => $this->getFechaAlta(),
            "resetPass"  => $this->getResetPass()
        ];
    }
}
