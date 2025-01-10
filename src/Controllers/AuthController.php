<?php
namespace HealthTracker\Controllers;

use HealthTracker\Controllers\Controller;

class AuthController extends Controller {
    private $users;

    public function __construct() {
        global $mongodb_database;
        $this->users = $mongodb_database->users;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->users->findOne(['email' => $email]);

            if ($user && password_verify($password, $user->password)) {
                $_SESSION['user'] = true;
                $_SESSION['user_id'] = (string)$user->_id;
                $_SESSION['user_name'] = $user->name;
                header('Location: /albi/dashboard');
                exit;
            } else {
                $error = "Email atau password salah";
                require __DIR__ . '/../../Views/auth/login.php';
            }
        } else {
            require __DIR__ . '/../../Views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'created_at' => new \MongoDB\BSON\UTCDateTime()
            ];

            try {
                $result = $this->users->insertOne($userData);
                if ($result->getInsertedCount() > 0) {
                    $_SESSION['user'] = true;
                    $_SESSION['user_id'] = (string)$result->getInsertedId();
                    $_SESSION['user_name'] = $userData['name'];
                    header('Location: /albi/dashboard');
                    exit;
                }
            } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
                if ($e->getCode() == 11000) { // Duplicate key error
                    $error = "Email sudah terdaftar";
                } else {
                    $error = "Gagal mendaftar";
                }
                require __DIR__ . '/../../Views/auth/register.php';
            }
        } else {
            require __DIR__ . '/../../Views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /albi');
        exit;
    }
}