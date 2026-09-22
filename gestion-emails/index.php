 <?php

$messageAjout = "";
$messageTraitement = "";

if (isset($_GET["traitement"])) {

    if ($_GET["traitement"] === "erreur") {

        $messageTraitement = "Erreur : aucun fichier n'a été sélectionné.";
    }
}

if (isset($_GET["ajout"])) {

    if ($_GET["ajout"] === "success") {

        $messageAjout = "L'adresse email a été ajoutée avec succès.";

    } elseif ($_GET["ajout"] === "invalid") {

        $messageAjout = "L'adresse email est invalide.";

    } elseif ($_GET["ajout"] === "existe") {

        $messageAjout = "Cette adresse email existe déjà.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
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
    <?php

if ($messageTraitement !== "") {
    echo "<p style='color:red;'>" . $messageTraitement . "</p>";
}

?>

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

    <hr>

<h2>Ajouter une nouvelle adresse email</h2>

<form action="ajouter.php" method="POST">

    <label for="email">
        Adresse email :
    </label>

    <input
        type="email"
        id="email"
        name="email"
        placeholder="example@gmail.com"
        required
    >

    <button type="submit">
        AJOUTER
    </button>

</form>
<?php

if ($messageAjout !== "") {
    echo "<p>" . $messageAjout . "</p>";
}

?>
<hr>

<h2>Envoyer des fichiers par email</h2>

<form action="envoyer.php" method="POST">

    <h3>Sélectionner les fichiers :</h3>

    <?php

    if (file_exists("EmailsT.txt")) {
        echo '
        <p>
            <input type="checkbox" name="fichiers[]" value="EmailsT.txt">
            EmailsT.txt
        </p>';
    }

    if (file_exists("EmailInvalide.txt")) {
        echo '
        <p>
            <input type="checkbox" name="fichiers[]" value="EmailInvalide.txt">
            EmailInvalide.txt
        </p>';
    }

    foreach (glob("domaines/*.txt") as $fichier) {

        $nomFichier = basename($fichier);

        echo '
        <p>
            <input type="checkbox" name="fichiers[]" value="' . $fichier . '">
            ' . $nomFichier . '
        </p>';
    }

    ?>

    <h3>Email destinataire :</h3>

    <input
        type="email"
        name="destinataire"
        placeholder="example@gmail.com"
        required
    >

    <br><br>

    <button type="submit">
        ENVOYER
    </button>

</form>


<hr>

<h2>Envoyer un message aux adresses sélectionnées</h2>

<form
    action="envoyer_message.php"
    method="POST"
    enctype="multipart/form-data"
>

    <h3>Emails disponibles :</h3>

    <?php

    if (file_exists("EmailsT.txt")) {

        $emails = file("EmailsT.txt");

        foreach ($emails as $email) {

            $email = trim($email);

            if ($email !== "") {

                echo '
                <p>
                    <input
                        type="checkbox"
                        name="destinataires[]"
                        value="' . htmlspecialchars($email) . '"
                    >
                    ' . htmlspecialchars($email) . '
                </p>';
            }
        }
    }

    ?>

    <h3>Objet :</h3>

    <input
        type="text"
        name="objet"
        required
    >

    <h3>Message :</h3>

    <textarea
        name="message"
        rows="6"
        cols="50"
        required
    ></textarea>

    <h3>Pièce jointe (optionnelle) :</h3>

    <input
        type="file"
        name="piece_jointe"
    >

    <br><br>

    <button type="submit">
        ENVOYER
    </button>

</form>
    
</body>
</html>