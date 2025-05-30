<?php
// Connexion à la base de données
$host = 'localhost';
$db   = 'postyves';
$user = 'PostYves';
$pass = 'sK2L6C*4[kVaPFHH';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Si un statut est modifié via GET
    if (isset($_GET['changer_statut']) && isset($_GET['id']) && isset($_GET['nouveau'])) {
        $id = (int)$_GET['id'];
        $nouveau = $_GET['nouveau'];
        if (in_array($nouveau, ['todo', 'progress', 'done'])) {
            $stmt = $pdo->prepare("UPDATE taches SET statut = ? WHERE id = ?");
            $stmt->execute([$nouveau, $id]);
        }
        // Redirection pour éviter les répétitions de clic
        header("Location: index.php");
        exit;
    }

    // Récupérer toutes les tâches
    $stmt = $pdo->query("SELECT * FROM taches ORDER BY date_echeance DESC");
    $taches = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "<p>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Organiser les tâches par statut
$colonnes = [
    'todo' => [],
    'progress' => [],
    'done' => []
];
foreach ($taches as $tache) {
    $colonnes[$tache['statut']][] = $tache;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tâches par Statut</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .colonnes {
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }
        .colonne {
            border: 1px solid #ccc;
            padding: 10px;
            width: 30%;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .priorite {
            border: 1px solid #999;
            padding: 8px;
            margin-bottom: 10px;

            border-radius: 6px;
        }

        .p1 { background-color: #d4edda; color: #155724; } /* Très basse */
        .p2 { background-color: #c3e6cb; color: #155724; }
        .p3 { background-color: #ffeeba; color: #856404; } /* Moyenne */
        .p4 { background-color: #f8d7da; color: #721c24; } /* Haute */
        .p5 { background-color: #f5c6cb; color: #721c24; font-weight: bold; border: 1px solid red; }

        .tache p {
            margin: 5px 0;
        }
        .actions form {
            display: inline;
        }
    </style>
</head>
<body>
    <h1>Liste des Tâches par Statut</h1>

    <div class="colonnes">
        <?php
        $titres = ['todo' => 'À faire', 'progress' => 'En cours', 'done' => 'Terminé'];

        foreach ($colonnes as $statut => $tachesStatut):
        ?>
            <div class="colonne">
                <h2><?= $titres[$statut] ?></h2>
                <?php foreach ($tachesStatut as $tache): ?>
                    <div class="tache">
                        <strong><?= htmlspecialchars($tache['titre']) ?></strong>
                        <p><?= htmlspecialchars($tache['description']) ?></p>
                        <p class="priorite p<?= (int)$tache['priorite'] ?>">Priorité : <?= $tache['priorite'] ?></p>
                        <p><small>Échéance : <?= htmlspecialchars($tache['date_echeance']) ?></small></p>

                        <div class="actions">
                            <?php if ($statut !== 'todo'): ?>
                                <a href="?changer_statut=1&id=<?= $tache['id'] ?>&nouveau=todo">⬅️ À faire</a>
                            <?php endif; ?>
                            <?php if ($statut !== 'progress'): ?>
                                <a href="?changer_statut=1&id=<?= $tache['id'] ?>&nouveau=progress">🔄 En cours</a>
                            <?php endif; ?>
                            <?php if ($statut !== 'done'): ?>
                                <a href="?changer_statut=1&id=<?= $tache['id'] ?>&nouveau=done">✅ Terminé</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
<br>
    <button>
        <a href="ajouter.php">Ajouter une tache</a>
    </buton>
</body>
</html>