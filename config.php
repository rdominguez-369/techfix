<?php

declare(strict_types=1);

date_default_timezone_set('Europe/Madrid');

ini_set('display_errors', '1');
error_reporting(E_ALL);

function escapar(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}