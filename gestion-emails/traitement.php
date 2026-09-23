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

        // 1. Vérifier la forme de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            file_put_contents(
                "EmailInvalide.txt",
                $email . PHP_EOL,
                FILE_APPEND
            );

            continue;
        }

        // 2. Récupérer le domaine
        $parties = explode("@", $email);
        $domaine = $parties[1];

        // 3. Vérifier si le domaine existe
        if (!checkdnsrr($domaine, "A")) {

            file_put_contents(
                "EmailInvalide.txt",
                $email . PHP_EOL,
                FILE_APPEND
            );

            continue;
        }

        // Si tout est valide
        $emailsValides[] = $email;
    }
}


// Supprimer les doublons
$emailsValides = array_unique($emailsValides);


// Réécrire emails.txt avec uniquement les emails valides
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


// Créer le dossier domaines s'il n'existe pas
if (!is_dir("domaines")) {
    mkdir("domaines");
}


// Vider les anciens fichiers de domaines
foreach (glob("domaines/*.txt") as $fichier) {
    unlink($fichier);
}


// Classer les emails par domaine
foreach ($emailsValides as $email) {

    $parties = explode("@", $email);

    $domaine = $parties[1];

    // Exemple : gmail.com → gmail
    $nomDomaine = explode(".", $domaine)[0];

    $fichier = "domaines/" . $nomDomaine . ".txt";

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