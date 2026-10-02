<?php

require_once __DIR__ . '/config.php';

$solicitud = $_GET['solicitud'] ?? null;
$cliente = $_GET['cliente'] ?? 'Cliente Anónimo';

$solicitudValidada = filter_var(
    $solicitud,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($solicitudValidada === false) {
    http_response_code(400);
    exit('Error: el número de solicitud debe ser un entero positivo.');
}

$clienteLimpio = trim($cliente);

if ($clienteLimpio === '') {
    $clienteLimpio = 'Cliente Anónimo';
}

$clienteMayusculas = mb_strtoupper($clienteLimpio, 'UTF-8');

$longitudCliente = mb_strlen($clienteLimpio, 'UTF-8');

