<?php
/**
 * Envuelve filtros ->where('user_id', …$effectiveUserId) en ->when(restrict…).
 * Ejecutar: php tools/apply-when-user-scope.php
 */
declare(strict_types=1);

$dir = dirname(__DIR__) . '/app/Http/Controllers/ApiControllers';
$when = "->when(\\App\\Helpers\\GestorHelper::restrictQueriesByOwnerUserId(), function (\$q) use (\$request) { return \$q->where('user_id', \\App\\Helpers\\GestorHelper::getUserId(\$request)); })";

foreach (glob($dir . '/*.php') as $f) {
    $o = file_get_contents($f);
    $c = str_replace("->where('user_id', '=', \$effectiveUserId)", $when, $o);
    $c = str_replace("->where('user_id', \$effectiveUserId)", $when, $c);
    $c = str_replace('->where("user_id", $effectiveUserId)', $when, $c);
    $c = str_replace('->where("user_id", "=", $effectiveUserId)', $when, $c);

    $c = str_replace(
        'if (!$effectiveUserId || (int) $fr->user_id !== (int) $effectiveUserId)',
        'if (!$effectiveUserId || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $fr->user_id))',
        $c
    );
    $c = str_replace(
        'if (!$effectiveUserId || (int) $frU->user_id !== (int) $effectiveUserId)',
        'if (!$effectiveUserId || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $frU->user_id))',
        $c
    );
    $c = str_replace(
        'if (!$factura || (int) $factura->user_id !== (int) $effectiveUserId)',
        'if (!$factura || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $factura->user_id))',
        $c
    );
    $c = str_replace(
        'if (!$effectiveUserId || (int) $liqU->user_id !== (int) $effectiveUserId)',
        'if (!$effectiveUserId || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $liqU->user_id))',
        $c
    );
    $c = str_replace(
        'if (!$origen || (int) $origen->user_id !== (int) $effectiveUserId)',
        'if (!$origen || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $origen->user_id))',
        $c
    );
    $c = str_replace(
        'if (!$recibo || (int) $recibo->user_id !== (int) $effectiveUserId)',
        'if (!$recibo || !\\App\\Helpers\\GestorHelper::ownsUserIdRow($request, $recibo->user_id))',
        $c
    );

    if ($c !== $o) {
        file_put_contents($f, $c);
        echo basename($f) . PHP_EOL;
    }
}
