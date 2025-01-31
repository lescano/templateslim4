<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Database\Basics;



class Users extends Basics
{
    public function __construct()
    {
        parent::__construct('users');
    }

    /**
     * Crear un usuario con lógica personalizada, por ejemplo, hash de contraseña.
     *
     * @param array $data Datos del usuario a insertar.
     * @return int|null ID del registro insertado o null si falla.
     */
    public function create(array $data): ?int
    {
        // Usar el método create de Basics
        return parent::create($data);
    }

}
