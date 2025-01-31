<?php

declare(strict_types=1);

use DI\ContainerBuilder;

// Cargar autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Inicializar el contenedor
$containerBuilder = new ContainerBuilder();
$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/app/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

// Probar la conexión a la base de datos
try {
    $db = $container->get('db'); // Obtener el objeto PDO
    echo "Conexión exitosa: " . $db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
