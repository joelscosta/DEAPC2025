<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo "Todos os campos são obrigatórios.";
    exit;
}

// Criação da base de dados temporária em ficheiro (ou liga à tua base real)
$ficheiro = '../dados/utilizadores.txt'; // ou usa MySQL mais tarde

// Verifica se já existe
$registos = file_exists($ficheiro) ? file($ficheiro, FILE_IGNORE_NEW_LINES) : [];

foreach ($registos as $linha) {
    list($user) = explode(';', $linha);
    if ($user === $username) {
        echo "Utilizador já existe!";
        exit;
    }
}

// Guarda os dados (nunca se deve guardar a password em texto plano num projeto real!)
file_put_contents($ficheiro, "$username;$password\n", FILE_APPEND);
echo "Conta criada com sucesso. <a href='../login_form.html'>Fazer login</a>";
?>