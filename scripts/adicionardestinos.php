<?php

$databaseFile = 'database.sqlite';

$destinations = [
    [
        'name' => 'Paris',
        'description' => 'A cidade do amor, famosa pela Torre Eiffel e rica história.',
        'image_url' => 'images/paris.jpg'
    ],
    [
        'name' => 'Kyoto',
        'description' => 'Antiga capital do Japão, conhecida por seus templos, jardins e gueixas.',
        'image_url' => 'images/kyoto.jpg'
    ],
    [
        'name' => 'Rio de Janeiro',
        'description' => 'Cidade vibrante com praias deslumbrantes, montanhas e o Cristo Redentor.',
        'image_url' => 'images/riodejaneiro.jpg'
    ],
    [
        'name' => 'Sydney',
        'description' => 'Cidade icónica conhecida pela sua Ópera, Ponte da Baía e belas praias.',
        'image_url' => 'images/sydney.jpg'
    ]
];

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT OR IGNORE INTO destinations (name, description, image_url) VALUES (:name, :description, :image_url)");

    foreach ($destinations as $destination) {
        $stmt->bindParam(':name', $destination['name']);
        $stmt->bindParam(':description', $destination['description']);
        $stmt->bindParam(':image_url', $destination['image_url']);
        $stmt->execute();
    }

    echo "Destinos de exemplo adicionados ou já existentes!";

} catch (PDOException $e) {
    echo "Erro ao adicionar destinos: " . $e->getMessage();
}

?>