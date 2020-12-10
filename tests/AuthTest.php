<?php

use PHPUnit\Framework\TestCase;
use App\Auth;

class AuthTest extends TestCase
{
    /**
     * @var Auth
     */
    private $auth;
    private $session = [];

    /**
     * @before
     */
    public function setAuth() {
        // sqlite::memory means I will use a fictive database and will recreate it at each test.
        $pdo = new PDO ("sqlite::memory:", null, null, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Create and insert values into the fictive db
        $pdo->query('CREATE TABLE users (id INTEGER, username TEXT, password TEXT)');
        for ($i = 1; $i <= 10; $i++) {
            $password = password_hash("user$i", PASSWORD_BCRYPT);
            $pdo->query("INSERT INTO users (id, username, password) VALUES ($i, 'user$i', '$password')");
        }
        $this->auth = new Auth($pdo, "/login", $this->session);

    }

    public function testLoginWithWrongUsername()
    {
        $this->assertNull($this->auth->login('aze', 'aze'));
    }

    public function testLoginWithWrongPassword()
    {
        $this->assertNull($this->auth->login('user1', 'aze'));
    }

    public function testLoginSuccess()
    {
        $this->assertObjectHasAttribute("username", $this->auth->login('user1', 'user1'));
        $this->assertEquals(1, $this->session['auth']);
    }
}