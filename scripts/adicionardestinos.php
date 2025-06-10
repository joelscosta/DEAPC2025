<?php

$databaseFile = 'database.sqlite';

$destinations = [
    [
        'name' => 'Paris',
        'description' => 'A cidade do amor, famosa pela Torre Eiffel e rica história.',
        'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Seine%2C_Paris-2010-07-06.jpg/800px-La_Tour_Eiffel_vue_de_la_Seine%2C_Paris-2010-07-06.jpg' // Exemplo de URL de imagem
    ],
    [
        'name' => 'Kyoto',
        'description' => 'Antiga capital do Japão, conhecida por seus templos, jardins e gueixas.',
        'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Kinkaku-ji-breeze.jpg/800px-Kinkaku-ji-breeze.jpg'
    ],
    [
        'name' => 'Rio de Janeiro',
        'description' => 'Cidade vibrante com praias deslumbrantes, montanhas e o Cristo Redentor.',
        'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/df/Cristo_Redentor_-_Rio_de_Janeiro%2C_Brasil.jpg/800px-Cristo_Redentor_-_Rio_de_Janeiro%2C_Brasil.jpg'
    ],
    [
        'name' => 'Sydney',
        'description' => 'Cidade icónica conhecida pela sua Ópera, Ponte da Baía e belas praias.',
        'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Sydney_Opera_House_and_Harbour_Bridge.jpg/800px-Sydney_Opera_House_and_Harbour_Bridge.jpg'
    ]
];

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Usa INSERT OR IGNORE para evitar duplicatas se o script for executado várias vezes
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