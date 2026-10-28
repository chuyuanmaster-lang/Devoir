<?php
require_once 'connexion.php';

$id_edit = null;
$titre_edit = '';
$auteur_edit = '';
$date_edit = '';

if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM exercice WHERE id = ?');
    $stmt->execute([$id_edit]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $titre_edit = $row['titre'];
        $auteur_edit = $row['auteur'];
        $date_edit = $row['date_creation'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $date_creation = $_POST['date_creation'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('UPDATE exercice SET titre = ?, auteur = ?, date_creation = ? WHERE id = ?');
        $stmt->execute([$titre, $auteur, $date_creation, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO exercice (titre, auteur, date_creation) VALUES (?, ?, ?)');
        $stmt->execute([$titre, $auteur, $date_creation]);
    }
    header('Location: index.php');
    exit();
}

$stmt = $pdo->query('SELECT * FROM exercice');
$exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Exercices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        fieldset {
            width: 400px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 15px;
        }
        legend {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: inline-block;
            width: 140px;
        }
        .form-group input {
            padding: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #3b82f6;
            color: white;
        }
        th, td {
            border: 1px solid #ffffff;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f59e0b;
            color: black;
        }
        a.btn {
            color: white;
            text-decoration: none;
            padding: 2px 6px;
            border: 1px solid white;
            display: inline-block;
        }
    </style>
</head>
<body>

<fieldset>
    <legend><?php echo $id_edit ? 'Modifier un exercice' : 'Ajouter un exercice'; ?></legend>
    <form method="POST" action="index.php">
        <?php if ($id_edit): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_edit); ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Titre de l'exercice</label>
            <input type="text" name="titre" value="<?php echo htmlspecialchars($titre_edit); ?>" required>
        </div>
        <div class="form-group">
            <label>Auteur de l'exercice</label>
            <input type="text" name="auteur" value="<?php echo htmlspecialchars($auteur_edit); ?>" required>
        </div>
        <div class="form-group">
            <label>Date création</label>
            <input type="date" name="date_creation" value="<?php echo htmlspecialchars($date_edit); ?>" required>
        </div>
        <button type="submit"><?php echo $id_edit ? 'Modifier' : 'Envoyer'; ?></button>
    </form>
</fieldset>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>titre</th>
            <th>auteur</th>
            <th>date_creation</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exercices as $exe): ?>
            <tr>
                <td><?php echo htmlspecialchars($exe['id']); ?></td>
                <td><?php echo htmlspecialchars($exe['titre']); ?></td>
                <td><?php echo htmlspecialchars($exe['auteur']); ?></td>
                <td><?php echo htmlspecialchars($exe['date_creation']); ?></td>
                <td><a class="btn" href="index.php?edit=<?php echo $exe['id']; ?>">Modifier</a></td>
                <td><a class="btn" href="supp_exe.php?id=<?php echo $exe['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet exercice ?');">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>