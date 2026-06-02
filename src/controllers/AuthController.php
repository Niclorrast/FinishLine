<?php

require_once __DIR__ . '/../models/usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $usuarioModel;

    //constructor
    public function __construct($conn) {
        $this->usuarioModel = new Usuario($conn);
    }

    //Iniciar sesion
    public function login(string $correo, string $password): bool {
        $usuario = $this->usuarioModel->login($correo, $password);

        if ($usuario) {
            $_SESSION['usuario'] = [
                'id'     => $usuario['id_usuario'],
                'nombre' => $usuario['Nombre'],
                'correo' => $usuario['Correo'],
                'rol'    => $usuario['Rol']
            ];
            return true;
        }

        return false;
    }

    //registrarse
    public function registro(array $datos): array {
        //Restriccion de vacio para los campos: nombre, correo, apellido, password
        if (empty($datos['nombre']) || empty($datos['apellido']) ||
            empty($datos['correo']) || empty($datos['password'])) {
            return ['error' => 'Todos los campos son obligatorios'];
        }

        if ($this->usuarioModel->existeCorreo($datos['correo'])) {
            return ['error' => 'El correo ya está registrado'];
        }

        $resultado = $this->usuarioModel->registrar(
            $datos['nombre'],
            $datos['apellido'],
            $datos['telefono'] ?? '',
            $datos['correo'],
            $datos['password']
        );

        return $resultado
            ? ['success' => 'Usuario registrado correctamente']
            : ['error'   => 'Error al registrar el usuario'];
    }

    //Cerrar sesision destruyendo la que estaba
    public function logout(): void {
        session_destroy();
        header('Location: /index.php');
        exit();
    }
}