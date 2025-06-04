$ficheiro = '../dados/utilizadores.txt';
$valido = false;

if (file_exists($ficheiro)) {
    $registos = file($ficheiro, FILE_IGNORE_NEW_LINES);
    foreach ($registos as $linha) {
        list($user, $pass) = explode(';', $linha);
        if ($user === $username && $pass === $password) {
            $valido = true;
            break;
        }
    }
}

if ($valido) {
    echo "<p style='color:green;'>Login efetuado com sucesso!</p>";
} else {
    echo "<p style='color:red;'>Utilizador ou senha inválidos!</p>";
}