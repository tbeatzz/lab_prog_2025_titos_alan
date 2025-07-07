<?php

namespace app\core\services;

use app\core\models\dao\UsuarioDao;
use app\core\models\dto\UsuarioDto;
use app\libs\database\Connection;

final class AuthenticationService {

    private UsuarioDao $dao;

    public function __construct() {
        $this->dao = new UsuarioDao(Connection::get());
    }

    /**
     * Autentica al usuario con su cuenta y clave.
     * @param string $cuenta
     * @param string $clave
     * @return UsuarioDto
     * @throws \Exception si la cuenta no existe, la clave no es válida, o está deshabilitada.
     */
    public function login(string $cuenta, string $clave): UsuarioDto {
        $data = $this->dao->findByCuenta($cuenta);
        if (!$data) {
            throw new \Exception("Cuenta inexistente.");
        }

        
        $usuario = new UsuarioDto($data);

        if (!password_verify($clave, $usuario->getClave())) {
            throw new \Exception("Cuenta o clave incorrecta.");
        }

        if ((int)$usuario->getEstado() === 0) {
            throw new \Exception("La cuenta está deshabilitada.");
        }

        // Sesión mínima
        $_SESSION["usuario"] = [
            "id" => $usuario->getId(),
            "cuenta" => $usuario->getCuenta(),
            "nombres" => $usuario->getNombres(),
            "perfil" => $usuario->getPerfil()
        ];

        return new UsuarioDto($usuario->toArray());
    }

    /**
     * Cierra la sesión actual.
     */
    public function logout(): void {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Verifica si hay un usuario autenticado.
     * @return bool
     */
    public function isAuthenticated(): bool {
        return isset($_SESSION["usuario"]);
    }

    /**
     * Obtiene los datos del usuario autenticado.
     * @return array|null
     */
    public function currentUser(): ?array {
        return $_SESSION["usuario"] ?? null;
    }
}
