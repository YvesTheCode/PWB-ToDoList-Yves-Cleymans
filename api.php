<?php
// En-têtes pour JSON
header('Content-Type: application/json');

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

    // Préparation de la requête
    $sql = "SELECT * FROM taches WHERE 1";
    $params = [];

    // Filtrage par statut (ex: ?statut=todo)
    if (isset($_GET['statut']) && in_array($_GET['statut'], ['todo', 'progress', 'done'])) {
        $sql .= " AND statut = ?";
        $params[] = $_GET['statut'];
    }

    // Filtrage par priorité (ex: ?priorite=5 ou ?priorite=haute)
    if (isset($_GET['priorite'])) {
        $priorite = $_GET['priorite'];
        if (is_numeric($priorite) && $priorite >= 0 && $priorite <= 5) {
            $sql .= " AND priorite = ?";
            $params[] = $priorite;
        } elseif ($priorite === 'haute') {
            $sql .= " AND priorite >= 4";
        } elseif ($priorite === 'basse') {
            $sql .= " AND priorite <= 2";
        }
    }

    // Exécution
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $taches = $stmt->fetchAll();

    echo json_encode($taches, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}