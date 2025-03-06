<?php
require '../vendor/autoload.php';

use OTPHP\TOTP;
use Firebase\JWT\JWT;

header('Content-Type: application/json');

$config = require '../config/config.php';

// 📌 Capturar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 📌 Capturar los datos enviados por el usuario
$inputUser = $_POST['user'] ?? '';
$inputOTP = $_POST['otp'] ?? '';

// 📌 Validar usuario
if ($inputUser !== 'prueba123') {
    echo json_encode(['error' => 'Usuario no válido']);
    exit;
}

// 📌 Recuperar el secreto OTP desde `user_secret.txt`
$secretFile = '../config/user_secret.txt';
if (!file_exists($secretFile)) {
    echo json_encode(['error' => 'No se encontró el secreto OTP']);
    exit;
}

$secret = trim(file_get_contents($secretFile));

if (!$secret) {
    echo json_encode(['error' => 'El secreto OTP está vacío o es inválido']);
    exit;
}

// 📌 Crear el objeto OTP con el secreto correcto
$otp = TOTP::create($secret);

// 📌 Verificar el código OTP ingresado
if (!$otp->verify($inputOTP)) {
    echo json_encode(['error' => 'Código OTP incorrecto']);
    exit;
}

// 📌 Generar el JWT si el OTP es correcto
$payload = [
    'iss' => $config['issuer'],
    'sub' => $inputUser,
    'exp' => time() + $config['expire_time'],
];

$jwt = JWT::encode($payload, $config['jwt_secret'], 'HS256');

echo json_encode(['token' => $jwt]);
exit;
