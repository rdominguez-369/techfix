<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/OrdenTrabajo.php';

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

$orden = new OrdenTrabajo(
    numeroSolicitud: $solicitudValidada,
    cliente: $clienteMayusculas,
    tipoReparacion: TipoReparacion::DIAGNOSTICO
);

try {
    $presupuesto = calcularPresupuesto(
        manoObra: 50.0,
        recambios: 30.0,
        iva: 21.0
    );

    echo '<pre>';
    var_dump($orden);
    echo 'Presupuesto final: ' . number_format($presupuesto, 2) . ' €';
    echo '</pre>';
} catch (InvalidArgumentException $e) {
    echo 'Error: ' . $e->getMessage();
}