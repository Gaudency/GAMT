<?php

// Script para verificar permisos de directorios en Laravel

echo "Verificando permisos de directorios...\n";

$directories = [
    'storage/app',
    'storage/app/keys',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($directories as $directory) {
    if (!file_exists($directory)) {
        echo "Creando directorio: $directory\n";
        if (!mkdir($directory, 0755, true)) {
            echo "ERROR: No se pudo crear el directorio: $directory\n";
        }
    }

    echo "Verificando permisos para: $directory\n";

    // En Windows, los permisos funcionan diferente
    // Solo comprobamos que podemos escribir en el directorio
    if (!is_writable($directory)) {
        echo "ERROR: El directorio $directory no tiene permisos de escritura\n";
    } else {
        echo "OK: El directorio $directory tiene permisos de escritura\n";
    }
}

// Intenta crear un archivo de prueba en el directorio de claves
$keysDirectory = 'storage/app/keys';
if (!file_exists($keysDirectory)) {
    echo "Creando directorio para claves: $keysDirectory\n";
    if (!mkdir($keysDirectory, 0755, true)) {
        echo "ERROR: No se pudo crear el directorio para claves\n";
    } else {
        echo "Directorio para claves creado correctamente\n";
    }
}

$testFile = "$keysDirectory/test.txt";
if (file_put_contents($testFile, "Prueba de escritura: " . date('Y-m-d H:i:s'))) {
    echo "OK: Se pudo escribir en el directorio de claves\n";
    unlink($testFile); // Eliminar archivo de prueba
} else {
    echo "ERROR: No se pudo escribir en el directorio de claves\n";
}

echo "\nVerificación completada.\n";
