<?php

namespace Database\Models;

class User {
    private int $id;
    private string $login;
    private string $password_hash;

    function __construct(int $id, string $login, string $password_hash) {
        $this->id = $id;
        $this->login = $login;
        $this->password_hash = $password_hash;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function validatePassword(string $password): bool {
        return password_verify($password, $this->password_hash);
    }
}

class UserFactory {
    static function createUser(string $login, string $password): User {
        // TODO: insert to database
        return new User(0, $login, password_hash($password, PASSWORD_BCRYPT));
    }
}