<?php
require __DIR__ . '/../config.php';

class UserAuth
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function register($nombre, $apellido, $correo, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE correo = ?");
        $stmt->execute([$correo]);

        if ($stmt->rowCount() > 0) {
            return ['error' => 'El correo ya está registrado.'];
        }

        $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("INSERT INTO users (nombre, apellido, correo, contraseña) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$nombre, $apellido, $correo, $hashed_password]);

        if ($success) {
            return ['success' => 'Usuario registrado correctamente.'];
        } else {
            return ['error' => 'Error al registrar el usuario.'];
        }
    }

    public function login($correo, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT id, correo, contraseña FROM users WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($contrasena, $user['contraseña'])) {
                $_SESSION['idUsuario'] = $user['id'];
                $_SESSION['correo'] = $user['correo'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_apellido'] = $user['apellido'];
                return ['success' => 'Inicio de sesión exitoso.'];
            } else {
                return ['error' => 'Correo o contraseña incorrectos.'];
            }
        } else {
            return ['error' => 'Usuario no registrado. <a href="registro.php">Regístrate aquí</a>.'];
        }
    }
}
