<?php

// Este script ayuda a diagnosticar problemas con Telescope
echo "Diagnóstico de Laravel Telescope\n";
echo "===============================\n\n";

// Verificar archivo .env
$env = file_exists('.env') ? file_get_contents('.env') : '';
$isTelescopeEnabled = strpos($env, 'TELESCOPE_ENABLED=true') !== false;
echo "TELESCOPE_ENABLED en .env: " . ($isTelescopeEnabled ? "SÍ" : "NO") . "\n";

// Verificar entorno
$isLocal = strpos($env, 'APP_ENV=local') !== false;
echo "APP_ENV=local en .env: " . ($isLocal ? "SÍ" : "NO") . "\n";

// Verificar base de datos
$dbConnection = strpos($env, 'DB_CONNECTION=') !== false ? 
    substr($env, strpos($env, 'DB_CONNECTION=') + 14, 
    strpos($env, "\n", strpos($env, 'DB_CONNECTION=')) - (strpos($env, 'DB_CONNECTION=') + 14)) : '';
echo "Conexión DB: $dbConnection\n";

// Verificar archivo de provider
$providerPath = 'app/Providers/TelescopeServiceProvider.php';
$providerExists = file_exists($providerPath);
echo "TelescopeServiceProvider existe: " . ($providerExists ? "SÍ" : "NO") . "\n";

// Verificar configuración
$configPath = 'config/telescope.php';
$configExists = file_exists($configPath);
echo "Archivo config/telescope.php existe: " . ($configExists ? "SÍ" : "NO") . "\n";

// Verificar tablas
if (function_exists('mysqli_connect') && $dbConnection == 'mysql') {
    preg_match('/DB_HOST=([^\n]+)/', $env, $hostMatches);
    preg_match('/DB_PORT=([^\n]+)/', $env, $portMatches);
    preg_match('/DB_DATABASE=([^\n]+)/', $env, $dbMatches);
    preg_match('/DB_USERNAME=([^\n]+)/', $env, $userMatches);
    preg_match('/DB_PASSWORD=([^\n]+)/', $env, $passMatches);
    
    $host = isset($hostMatches[1]) ? $hostMatches[1] : 'localhost';
    $port = isset($portMatches[1]) ? $portMatches[1] : '3306';
    $database = isset($dbMatches[1]) ? $dbMatches[1] : '';
    $username = isset($userMatches[1]) ? $userMatches[1] : '';
    $password = isset($passMatches[1]) ? $passMatches[1] : '';
    
    $conn = @mysqli_connect($host, $username, $password, $database, $port);
    if ($conn) {
        $tablesResult = mysqli_query($conn, "SHOW TABLES LIKE 'telescope%'");
        $tableCount = mysqli_num_rows($tablesResult);
        echo "Tablas telescope encontradas: $tableCount\n";
        mysqli_close($conn);
    } else {
        echo "Error conectando a la base de datos\n";
    }
}

echo "\nPara acceder a Telescope, navega a: http://tuapp.test/telescope\n";
echo "Si encuentras errores, revisa los permisos en TelescopeServiceProvider.php\n"; 