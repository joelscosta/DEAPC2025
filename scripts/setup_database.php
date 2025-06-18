<?php
// Mostrar erros para ajudar no debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Abrir/criar a base de dados SQLite
$db = new SQLite3('database.sqlite');

// Confirmação
echo "<p>Base de dados 'database.sqlite' aberta/criada com sucesso.</p>";

// Criar tabela 'users'
if ($db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    full_name TEXT DEFAULT '',
    email TEXT DEFAULT '',
    phone TEXT DEFAULT ''
)")) {
    echo "<p>Tabela 'users' verificada/criada.</p>";
} else {
    echo "<p>Erro ao criar tabela 'users': " . $db->lastErrorMsg() . "</p>";
}

// Criar tabela 'destinations'
if ($db->exec("CREATE TABLE IF NOT EXISTS destinations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    description TEXT,
    image_url TEXT,
    price INTEGER DEFAULT 0
)")) {
    echo "<p>Tabela 'destinations' verificada/criada com o campo 'price'.</p>";
} else {
    echo "<p>Erro ao criar tabela 'destinations': " . $db->lastErrorMsg() . "</p>";
}

// Criar tabela 'bookings'
if ($db->exec("CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    destination_id INTEGER NOT NULL,
    booking_date TEXT NOT NULL,
    travel_date TEXT NOT NULL,
    num_travelers INTEGER NOT NULL DEFAULT 1,
    status TEXT NOT NULL DEFAULT 'Confirmada',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
)")) {
    echo "<p>Tabela 'bookings' verificada/criada.</p>";
} else {
    echo "<p>Erro ao criar tabela 'bookings': " . $db->lastErrorMsg() . "</p>";
}

// Mostrar todas as tabelas existentes
echo "<h3>Tabelas existentes na base de dados:</h3>";
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "🟢 " . $row['name'] . "<br>";
}

unset($db);
?>
