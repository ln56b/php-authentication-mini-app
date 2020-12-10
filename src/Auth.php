<?php

namespace App;

use App\Exception\ForbiddenException;
use PDO;

class Auth
{
    private $pdo;
    private $loginPath;
    private $session;

    // &$session means a reference to the array of sessions
    public function __construct(PDO $pdo, string $loginPath, array &$session)
    {
        $this->pdo = $pdo;
        $this->loginPath = $loginPath;
        $this->session = &$session;
    }

    public function login(string $username, string $password): ?User
    {
        // Find user from username
        $query = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $query->execute(['username' => $username]);
        $user = $query->fetchObject(User::class);
        if ($user === false) {
            return null;
        }
        // Check password
        if (password_verify($password, $user->password)) {
            $this->session['auth'] = $user->id;
            return $user;
        }
        return null;
    }

    public function user(): ?User
    {
        $id = $this->session['auth'] ?? null;
        if ($id === null) {
            return null;
        }
        $query = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$id]);
        $user = $query->fetchObject(User::class);
        return $user ?: null;
    }

    public function requireRole(string ...$roles): void {

        $user = $this->user();
        if ($user === null) {
            throw new ForbiddenException("Please login to see this page");
        }
        if (!in_array($user->role, $roles)) {
            $roles = implode(',', $roles);
            $role = $user->role;
            throw new ForbiddenException("Your role is $role. Only $roles can access to this page");
        }
    }
}
