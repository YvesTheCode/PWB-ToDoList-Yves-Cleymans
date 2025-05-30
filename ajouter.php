<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Post-it Submission</title>
</head>
<body>
    <h1>Ajouter un Post-it</h1>
    
    <form action="process.php" method="POST">
        <label for="nom">Titre :</label><br>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>

        <label for="date_echeance">Date d’échéance :</label><br>
        <input type="date" id="date_echeance" name="date_echeance"><br><br>

        <label for="priorite">Priorité :</label><br>
        <select id="priorite" name="priorite">
            <option value="1">1 - Très basse</option>
            <option value="2">2 - Basse</option>
            <option value="3" selected>3 - Moyenne</option>
            <option value="4">4 - Haute</option>
            <option value="5">5 - Urgente</option>
        </select><br><br>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>