<?php

$databaseFile = 'database.sqlite'; 
$destinationsToUpdate = [
    [
        'name' => 'Paris',
        'image_url' => 'images/paris.jpg'
    ],
    [
        'name' => 'Kyoto',
        'image_url' => 'images/kyoto.jpg'
    ],
    [
        'name' => 'Rio de Janeiro',
        'image_url' => 'images/riodejaneiro.jpg'
    ],
    [
        'name' => 'Sydney',
        'image_url' => 'images/sydney.jpg'
    ]
];

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("UPDATE destinations SET image_url = :image_url WHERE name = :name");

    foreach ($destinationsToUpdate as $destination) {
        $stmt->bindParam(':image_url', $destination['image_url']);
        $stmt->bindParam(':name', $destination['name']);

        if ($stmt->execute()) {
            echo "URL da imagem para '" . $destination['name'] . "' atualizado com sucesso para: " . $destination['image_url'] . "\n";
        } else {
            echo "Erro ao atualizar o URL da imagem para '" . $destination['name'] . "'.\n";
        }
    }

    echo "\nProcesso de atualização de URLs de imagem concluído!\n";

} catch (PDOException $e) {
    echo "Erro na base de dados: " . $e->getMessage() . "\n";
}

?>