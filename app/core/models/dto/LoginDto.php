<?php

namespace app\core\models\dto;

use app\core\models\dto\base\InterfaceDto;

/**
 * Descripción de LoginDto
 * 
 */
final class LoginDto implements InterfaceDto{

    private $userName, $password;

    public function getId(): int{
        return 0;
    }

    public function __construct($data = []){
        $this->setUserName($data["userName"] ?? "");
        $this->setPassword($data["password"] ?? "");
    }

    public function getUserName(): string{
        return $this->userName;
    }

    public function getPassword(): string{
        return $this->password;
    }

    private function setUserName(string $userName): void{
        $this->userName = $userName;
    }

    private function setPassword(string $password): void{
        $this->password = $password;
    }

    public function toArray(): array{
        return [
            "userName"  => $this->getUserName(),
            "password"  => $this->getPassword()
        ];
    }
}