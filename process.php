<?php
// Affichage des données envoyées via POST pour le débogage
echo "<h2>Contenu de \$_POST :</h2>";
var_dump($_POST);

// Paramètres de connexion à la base de données
$host = 'localhost';
$db   = 'postyves';
$user = 'PostYves';
$pass = 'sK2L6C*4[kVaPFHH';
$charset = 'utf8mb4';

// Construction du DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options de configuration pour PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Connexion à la base de données
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Vérifie que le champ 'nom' a été soumis
    if (!empty($_POST['nom'])) {
        $titre = trim($_POST['nom']);
        $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
        $statut = 'todo'; // Valeur par défaut
        $priorite = isset($_POST['priorite']) && in_array($_POST['priorite'], ['1','2','3','4','5'])
        ? (int)$_POST['priorite']: 3; // Valeur par défaut
        $date_echeance = !empty($_POST['date_echeance']) ? $_POST['date_echeance'] : null;

        // Prépare la requête
        $stmt = $pdo->prepare(
            'INSERT INTO `taches` (`titre`, `description`, `statut`, `priorite`, `date_echeance`) 
             VALUES (?, ?, ?, ?, ?)'
        );

        // Exécute avec les valeurs fournies
        $stmt->execute([$titre, $description, $statut, $priorite, $date_echeance]);

        echo "<p>Post-it ajouté avec succès !</p>";
    } else {
        echo "<p>Erreur : le champ 'nom' est vide.</p>";
    }

    
} catch (PDOException $e) {
    echo "<p>Erreur de base de données : " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "<button><a href='index.php'>Retour</a></buton>";

?>