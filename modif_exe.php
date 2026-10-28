<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $date_creation = $_POST['date_creation'];

    $stmt = $pdo->prepare('UPDATE exercice SET titre = ?, auteur = ?, date_creation = ? WHERE id = ?');
    $stmt->execute([$titre, $auteur, $date_creation, $id]);
}

header('Location: index.php');
exit();
?>