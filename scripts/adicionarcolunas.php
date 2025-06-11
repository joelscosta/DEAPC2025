<?php
$databaseFile = 'database.sqlite';
try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Adicionar nome
    $db->exec("ALTER TABLE users ADD COLUMN full_name TEXT DEFAULT '';");
    echo "Coluna 'full_name' adicionada à tabela 'users'.\n";

    // Adicionar email
    $db->exec("ALTER TABLE users ADD COLUMN email TEXT DEFAULT '';");
    echo "Coluna 'email' adicionada à tabela 'users'.\n";

    // Adicionar telemovel
    $db->exec("ALTER TABLE users ADD COLUMN phone TEXT DEFAULT '';");
    echo "Coluna 'phone' adicionada à tabela 'users'.\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "Algumas colunas já existem. Ignorando.\n";
    } else {
        echo "Erro ao adicionar colunas: " . $e->getMessage() . "\n";
    }
}
?>