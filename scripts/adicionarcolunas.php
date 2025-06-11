<?php
$databaseFile = 'database.sqlite';
try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Adicionar full_name
    $db->exec("ALTER TABLE users ADD COLUMN full_name TEXT DEFAULT '';");
    echo "Coluna 'full_name' adicionada à tabela 'users'.\n";

    // Adicionar email
    $db->exec("ALTER TABLE users ADD COLUMN email TEXT DEFAULT '';");
    echo "Coluna 'email' adicionada à tabela 'users'.\n";

    // Adicionar phone
    $db->exec("ALTER TABLE users ADD COLUMN phone TEXT DEFAULT '';");
    echo "Coluna 'phone' adicionada à tabela 'users'.\n";

} catch (PDOException $e) {
    // Se a coluna já existir, ALTER TABLE vai dar erro, mas podemos ignorar se for esse o caso
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "Algumas colunas já existem. Ignorando.\n";
    } else {
        echo "Erro ao adicionar colunas: " . $e->getMessage() . "\n";
    }
}
?>