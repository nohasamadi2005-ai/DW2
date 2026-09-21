<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des emails</title>
</head>

<body>

    <h1>GESTION DES EMAILS</h1>

    <h2>Importer un fichier</h2>

    <form action="upload.php" method="POST" enctype="multipart/form-data">

    <input type="file" name="fichier" required>

    <button type="submit">
        Envoyer
    </button>

</form>

    <hr>

    <h2>Traitement du fichier</h2>

    <a href="traitement.php">
        <button>Traiter le fichier</button>
    </a>

    <hr>

    <h2>Fichiers générés</h2>

    <?php

    if (file_exists("EmailsT.txt")) {
        echo '<p>
                <a href="EmailsT.txt" download>
                    Télécharger EmailsT.txt
                </a>
              </p>';
    }

    if (file_exists("EmailInvalide.txt")) {
        echo '<p>
                <a href="EmailInvalide.txt" download>
                    Télécharger EmailInvalide.txt
                </a>
              </p>';
    }

    foreach (glob("domaines/*.txt") as $fichier) {

        $nomFichier = basename($fichier);

        echo '<p>
                <a href="' . $fichier . '" download>
                    Télécharger ' . $nomFichier . '
                </a>
              </p>';
    }

    ?>
    
</body>
</html>