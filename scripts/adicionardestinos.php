<?php

$databaseFile = 'database.sqlite';

$destinations = [
    [
        'name' => 'Paris',
        'description' => 'A cidade do amor, famosa pela Torre Eiffel e rica história.',
        'image_url' => 'images/paris.jpg',
        'price' => 1200
    ],
    [
        'name' => 'Kyoto',
        'description' => 'Antiga capital do Japão, conhecida por seus templos, jardins e gueixas.',
        'image_url' => 'images/kyoto.jpg',
        'price' => 1500
    ],
    [
        'name' => 'Rio de Janeiro',
        'description' => 'Cidade vibrante com praias deslumbrantes, montanhas e o Cristo Redentor.',
        'image_url' => 'images/riodejaneiro.jpg',
        'price' => 1000
    ],
    [
        'name' => 'Sydney',
        'description' => 'Cidade icónica conhecida pela sua Ópera, Ponte da Baía e belas praias.',
        'image_url' => 'images/sydney.jpg',
        'price' => 1800
    ]
];

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insere com o campo price
    $stmt = $db->prepare("INSERT OR IGNORE INTO destinations (name, description, image_url, price) VALUES (:name, :description, :image_url, :price)");

    foreach ($destinations as $destination) {
        $stmt->bindParam(':name', $destination['name']);
        $stmt->bindParam(':description', $destination['description']);
        $stmt->bindParam(':image_url', $destination['image_url']);
        $stmt->bindParam(':price', $destination['price']);
        $stmt->execute();
    }

    echo "Destinos de exemplo adicionados ou já existentes!";

} catch (PDOException $e) {
    echo "Erro ao adicionar destinos: " . $e->getMessage();
}

?>
