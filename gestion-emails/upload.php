<?php

if (isset($_FILES["fichier"])) {

    $fichierTemporaire = $_FILES["fichier"]["tmp_name"];

    // Enregistrer le fichier sous le nom Emails.txt
    move_uploaded_file(
        $fichierTemporaire,
        "emails.txt"
    );

    echo "<h2>Fichier téléchargé avec succès !</h2>";

    echo '<a href="traitement.php">
            <button>Traiter le fichier</button>
          </a>';

} else {

    echo "Aucun fichier sélectionné.";

}

?>