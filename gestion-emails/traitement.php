<?php
if (!file_exists("emails.txt") || filesize("emails.txt") == 0) {

    header("Location: index.php?traitement=erreur");
    exit;
}



$emails = file("emails.txt");

$emailsValides = [];

// Vider EmailInvalide.txt avant chaque traitement
file_put_contents("EmailInvalide.txt", "");

foreach ($emails as $email) {

    $email = trim($email);

    if ($email !== "") {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailsValides[] = $email;

        } else {

            file_put_contents(
                "EmailInvalide.txt",
                $email . PHP_EOL,
                FILE_APPEND
            );
        }
    }
}
$emailsValides = array_unique($emailsValides);
file_put_contents(
    "emails.txt",
    $emailsValides
        ? implode(PHP_EOL, $emailsValides) . PHP_EOL
        : ""
);
// Trier les emails
sort($emailsValides);


// Enregistrer les emails triés dans EmailsT.txt
file_put_contents(
    "EmailsT.txt",
    $emailsValides
        ? implode(PHP_EOL, $emailsValides) . PHP_EOL
        : ""
);
if (!is_dir("domaines")) {
    mkdir("domaines");
}

// 9. Vider les anciens fichiers de domaines
foreach (glob("domaines/*.txt") as $fichier) {
    unlink($fichier);
}


foreach ($emailsValides as $email) {

    // Séparer l'email avec @
    $parties = explode("@", $email);

    // Récupérer le domaine
    $domaine = $parties[1];

    // Récupérer le nom du domaine
    // Exemple : gmail.com → gmail
    $nomDomaine = explode(".", $domaine)[0];

    // Nom du fichier
    $fichier = "domaines/" . $nomDomaine . ".txt";

    // Ajouter l'email dans le fichier
    file_put_contents(
        $fichier,
        $email . PHP_EOL,
        FILE_APPEND
    );
}


echo "<h2>Traitement terminé !</h2>";

echo "<h3>Emails valides sans doublons :</h3>";

foreach ($emailsValides as $email) {
    echo $email . "<br>";
}
echo '<br>';

echo '<a href="index.php">
        <button>Retour à la page d’accueil</button>
      </a>';


?>