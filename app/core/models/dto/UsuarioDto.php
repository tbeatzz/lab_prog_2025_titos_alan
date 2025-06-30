<?php

namespace app\core\models\dto;

use app\core\models\dto\base\InterfaceDto;

final class UsuarioDto implements InterfaceDto
{

    private $id, $apellido, $nombres, $cuenta, $perfil, $clave, $correo, $estado, $fechaAlta, $resetPass;

    /**
     * El constructor recibe un array asociativo con los datos del usuario.
     * Si no se proporciona un valor, se asigna un valor por defecto.
     *
     * @param array $data Array asociativo con los datos del usuario.
     */
    public function __construct(array $data = [])
    {
        $this->setId($data["id"] ?? 0);
        $this->setApellido($data["apellido"] ?? "");
        $this->setNombres($data["nombres"] ?? "");
        $this->setCuenta($data["cuenta"] ?? "");
        $this->setPerfil($data["perfil"] ?? "Operador");
        $this->setClave($data["clave"] ?? "");
        $this->setCorreo($data["correo"] ?? "");
        $this->setEstado($data["estado"] ?? 1);
        $this->setFechaAlta($data["fechaAlta"] ?? date("Y-m-d"));
        $this->setResetPass($data["resetPass"] ?? 0);
    }

    /***********************************************************
     * **********GETTERS DE CADA ATRIBUTO*********************
     ********************************************************/

    public function getId(): int{
        return $this->id;
    }
    public function getApellido(): string{
        return $this->apellido;
    }
    public function getNombres(): string{
        return $this->nombres;
    }
    public function getCuenta(): string{
        return $this->cuenta;
    }
    public function getPerfil(): string{
        return $this->perfil;
    }
    public function getClave(): string{
        return $this->clave;
    }
    public function getCorreo(): string{
        return $this->correo;
    }
    public function getEstado(): int{
        return $this->estado;
    }
    public function getFechaAlta(): string{
        return $this->fechaAlta;
    }
    public function getResetPass(): int{
        return $this->resetPass;
    }

    /***********************************************************
     * **********SETTERS DE CADA ATRIBUTO*********************
     ********************************************************/
    public function setId(int $id): void{
        $this->id = $id > 0 ? $id : 0;
    }
    public function setApellido(string $apellido): void{
        $this->apellido = (strlen(trim($apellido)) <= 100) ? trim($apellido) : "";
    }
    public function setNombres(string $nombres): void{
        $this->nombres = (strlen(trim($nombres)) <= 100) ? trim($nombres) : "";
    }
    public function setCuenta(string $cuenta): void{
        $this->cuenta = (strlen(trim($cuenta)) <= 20) ? trim($cuenta) : "";
    }
    public function setPerfil(string $perfil): void{
        $this->perfil = in_array(strtolower($perfil), ['administrador', 'operador']) ? strtolower($perfil) : 'operador';
    }
    public function setClave(string $clave): void{
        $this->clave = (strlen(trim($clave)) <= 255) ? trim($clave) : "";
    }
    public function setCorreo(string $correo): void{
        $this->correo = (filter_var(trim($correo), FILTER_VALIDATE_EMAIL)) ? trim($correo) : "";
    }
    public function setEstado(int $estado): void{
        $this->estado = ($estado === 1) ? 1 : 0;
    }
    public function setFechaAlta(string $fechaAlta): void{
        $this->fechaAlta = $fechaAlta;
    }
    public function setResetPass(int $resetPass): void{
        $this->resetPass = ($resetPass === 1) ? 1 : 0;
    }

    public function toArray(): array{
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