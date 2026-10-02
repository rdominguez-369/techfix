<?php

declare(strict_types=1);

enum TipoReparacion: string
{
    case DIAGNOSTICO = 'Diagnóstico';
    case SOFTWARE = 'Software';
    case HARDWARE = 'Hardware';
}

final readonly class OrdenTrabajo
{
    public function __construct(
        public int $numeroSolicitud,
        public string $cliente,
        public TipoReparacion $tipoReparacion
    ) {
    }
}

/**
 * Calcula el presupuesto final de una reparación.
 *
 * @param float $manoObra Precio de la mano de obra.
 * @param float $recambios Precio total de los recambios.
 * @param float $iva Porcentaje de IVA aplicado.
 *
 * @return float Importe total con IVA.
 *
 * @throws InvalidArgumentException Si algún importe es negativo.
 */
function calcularPresupuesto(
    float $manoObra,
    float $recambios,
    float $iva = 21.0
): float {
    if ($manoObra < 0 || $recambios < 0 || $iva < 0) {
        throw new InvalidArgumentException(
            'Los importes y el IVA no pueden ser negativos.'
        );
    }

    $subtotal = $manoObra + $recambios;

    return $subtotal + ($subtotal * $iva / 100);
}