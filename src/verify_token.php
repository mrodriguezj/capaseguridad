<?php
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$config = require '../config/config.php';

$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

if (!$token) {
    die(json_encode(['error' => 'Token no proporcionado']));
}

try {
    $decoded = JWT::decode($token, new Key($config['jwt_secret'], 'HS256'));
    echo json_encode(['message' => 'Bienvenido, ' . $decoded->sub]);
} catch (Exception $e) {
    die(json_encode(['error' => 'Token inválido o expirado']));
}
