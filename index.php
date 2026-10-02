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

$tasaAlmacenamiento = 0.05;

foreach ($catalogo as &$producto) {
    $producto['precio'] += $producto['precio'] * $tasaAlmacenamiento;
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

ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TechFix - Gestión de Reparaciones</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            background-color: #f4f6f8;
            color: #222;
        }

        h1,
        h2 {
            color: #1f4e79;
        }

        section {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #e9eef3;
        }

        .paginacion {
            margin-top: 20px;
        }

        .paginacion a {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <h1>TechFix</h1>
    <p>Portal Server-Side de Gestión de Reparaciones</p>

    <section>
        <h2>Orden de trabajo</h2>

        <p>
            <strong>N.º de solicitud:</strong>
            <?= escapar((string) $orden->numeroSolicitud) ?>
        </p>

        <p>
            <strong>Cliente:</strong>
            <?= escapar($orden->cliente) ?>
        </p>

        <p>
            <strong>Longitud del nombre:</strong>
            <?= escapar((string) $longitudCliente) ?> caracteres
        </p>

        <p>
            <strong>Tipo de reparación:</strong>
            <?= escapar($orden->tipoReparacion->value) ?>
        </p>

        <p>
            <strong>Descripción:</strong>
            <?= escapar($orden->tipoReparacion->descripcion()) ?>
        </p>
    </section>


    <section>
        <h2>Presupuesto</h2>

        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Importe</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Mano de obra</td>
                    <td><?= escapar(number_format(50.0, 2, ',', '.')) ?> €</td>
                </tr>

                <tr>
                    <td>Recambios</td>
                    <td><?= escapar(number_format(30.0, 2, ',', '.')) ?> €</td>
                </tr>

                <tr>
                    <td>IVA</td>
                    <td>21 %</td>
                </tr>

                <tr>
                    <td><strong>Total</strong></td>
                    <td>
                        <strong>
                            <?= escapar(number_format($presupuesto, 2, ',', '.')) ?> €
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>


    <section>
        <h2>Recambios disponibles</h2>

        <?php if (count($productosPagina) > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($productosPagina as $producto): ?>

                        <tr>
                            <td>
                                <?= escapar((string) $producto['id']) ?>
                            </td>

                            <td>
                                <?= escapar($producto['nombre']) ?>
                            </td>

                            <td>
                                <?= escapar(
                                    number_format(
                                        $producto['precio'],
                                        2,
                                        ',',
                                        '.'
                                    )
                                ) ?> €
                            </td>

                            <td>
                                <?= escapar((string) $producto['stock']) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php else: ?>

            <p>No hay recambios disponibles.</p>

        <?php endif; ?>

        <p>
            <strong>Valor total del inventario:</strong>

            <?= escapar(
                number_format(
                    $valorInventario,
                    2,
                    ',',
                    '.'
                )
            ) ?> €
        </p>


        <div class="paginacion">

            <strong>
                Página
                <?= escapar((string) $pagina) ?>
                de
                <?= escapar((string) $totalPaginas) ?>
            </strong>

            <br><br>

            <?php if ($pagina > 1): ?>

                <a href="?solicitud=<?= escapar((string) $solicitudValidada) ?>&cliente=<?= urlencode($clienteLimpio) ?>&pagina=<?= $pagina - 1 ?>">
                    ← Anterior
                </a>

            <?php endif; ?>


            <?php if ($pagina < $totalPaginas): ?>

                <a href="?solicitud=<?= escapar((string) $solicitudValidada) ?>&cliente=<?= urlencode($clienteLimpio) ?>&pagina=<?= $pagina + 1 ?>">
                    Siguiente →
                </a>

            <?php endif; ?>

        </div>

    </section>

</body>

</html>

<?php

$html = ob_get_clean();

echo $html;