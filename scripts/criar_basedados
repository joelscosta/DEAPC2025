<?php

$databaseFile = 'database.sqlite'; // Nome do arquivo do banco de dados

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Atualiza a tabela 'users' para incluir campos de perfil, se eles não existirem
    // Usamos ALTER TABLE para adicionar colunas sem perder dados existentes
    $db->exec("CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                full_name TEXT DEFAULT '',
                email TEXT DEFAULT '',
                phone TEXT DEFAULT ''
            )");

    // Adiciona as colunas se ainda não existirem (para quem já tinha a tabela users)
    // Usamos TRY-CATCH para ALTER TABLE pois SQLite não tem IF NOT EXISTS para colunas
    try {
        $db->exec("ALTER TABLE users ADD COLUMN full_name TEXT DEFAULT ''");
    } catch (PDOException $e) { /* Coluna já existe */ }
    try {
        $db->exec("ALTER TABLE users ADD COLUMN email TEXT DEFAULT ''");
    } catch (PDOException $e) { /* Coluna já existe */ }
    try {
        $db->exec("ALTER TABLE users ADD COLUMN phone TEXT DEFAULT ''");
    } catch (PDOException $e) { /* Coluna já existe */ }


    // Cria a tabela 'destinations' se ela não existir
    $db->exec("CREATE TABLE IF NOT EXISTS destinations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT,
                image_url TEXT
            )");

    // Cria a tabela 'bookings' se ela não existir
    $db->exec("CREATE TABLE IF NOT EXISTS bookings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                destination_id INTEGER NOT NULL,
                booking_date TEXT NOT NULL,
                travel_date TEXT NOT NULL,
                num_travelers INTEGER NOT NULL DEFAULT 1,
                status TEXT NOT NULL DEFAULT 'Confirmada',
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
            )");

    echo "Banco de dados e tabelas verificados/atualizados com sucesso em '$databaseFile'!";

} catch (PDOException $e) {
    echo "Erro ao criar/atualizar o banco de dados ou tabelas: " . $e->getMessage();
}

?>