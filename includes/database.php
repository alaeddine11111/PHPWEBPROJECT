<?php
// Configuration de la base de données
$db_config = [
    'host' => '127.0.0.1:3306',
    'dbname' => 'gestion_stocke', 
    'username' => 'root',
    'password' => ''
];

// Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']}", 
        $db_config['username'], 
        $db_config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>