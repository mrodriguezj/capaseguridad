<?php
require '../vendor/autoload.php';

use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

header('Content-Type: image/png');

$file_path = '../config/user_secret.txt';

// 📌 Leer el secreto desde user_secret.txt
$secret = trim(file_get_contents($file_path));

if (!$secret) {
    die("Error: No se pudo obtener el secreto OTP.");
}

// 📌 Crear el objeto OTP con el secreto correcto
$otp = TOTP::create($secret);
$otp->setLabel('prueba123');
$otp->setIssuer('MiApp');

// 📌 Generar el código QR con la URL de configuración OTP
$qrCode = new QrCode($otp->getProvisioningUri());
$writer = new PngWriter();
$result = $writer->write($qrCode);

echo $result->getString();
exit;
