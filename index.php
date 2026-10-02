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

$catalogo = [
    [
        'id' => 1,
        'nombre' => 'Disco SSD 1 TB',
        'precio' => 89.90,
        'stock' => 5
    ],
    [
        'id' => 2,
        'nombre' => 'Memoria RAM 16 GB',
        'precio' => 59.90,
        'stock' => 8
    ],
    [
        'id' => 3,
        'nombre' => 'Fuente de alimentación',
        'precio' => 74.50,
        'stock' => 0
    ],
    [
        'id' => 4,
        'nombre' => 'Ventilador CPU',
        'precio' => 29.90,
        'stock' => 12
    ],
    [
        'id' => 5,
        'nombre' => 'Tarjeta de red',
        'precio' => 24.90,
        'stock' => 0
    ],
    [
        'id' => 6,
        'nombre' => 'Pasta térmica',
        'precio' => 9.50,
        'stock' => 20
    ]
];

foreach ($catalogo as &$producto) {
    if ($producto['id'] === 1) {
        $producto['precio'] = 84.90;
    }

    if ($producto['id'] === 6) {
        $producto['stock'] = 25;
    }
}

unset($producto);

$productosDisponibles = array_filter(
    $catalogo,
    fn(array $producto): bool => $producto['stock'] > 0
);

$valorInventario = 0.0;

foreach ($productosDisponibles as $producto) {
    $valorInventario += $producto['precio'] * $producto['stock'];
}

$porPagina = 2;

$totalProductos = count($productosDisponibles);

$totalPaginas = max(
    1,
    (int) ceil($totalProductos / $porPagina)
);

$pagina = filter_var(
    $_GET['pagina'] ?? 1,
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'default' => 1,
            'min_range' => 1
        ]
    ]
);

$pagina = min($pagina, $totalPaginas);

$inicio = ($pagina - 1) * $porPagina;

$productosPagina = array_slice(
    array_values($productosDisponibles),
    $inicio,
    $porPagina
);

echo '<pre>';

echo "=== CATÁLOGO COMPLETO ===\n";
print_r($catalogo);

echo "\n=== PRODUCTOS DISPONIBLES ===\n";
print_r($productosDisponibles);

echo "\n=== VALOR DEL INVENTARIO ===\n";
echo number_format($valorInventario, 2, ',', '.') . " €\n";

echo "\n=== PAGINACIÓN ===\n";
echo "Página actual: {$pagina}\n";
echo "Total de páginas: {$totalPaginas}\n";

echo "\n=== PRODUCTOS DE ESTA PÁGINA ===\n";
print_r($productosPagina);

echo '</pre>';