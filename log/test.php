<?php
// Conectar a la base de datos SQLite
$db = new SQLite3('/var/www/html/coffee-soul/log/geoloc.sqlite');

// Consultar las tablas en la base de datos
$results = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

// Mostrar las tablas disponibles
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    echo "Tabla: " . $row['name'] . "\n";
}

// Cerrar la conexión
$db->close();
