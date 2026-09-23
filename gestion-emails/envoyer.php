<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


// 1. RÉCUPÉRER LES DONNÉES

$destinataire = $_POST["destinataire"] ?? "";
$fichiers = $_POST["fichiers"] ?? "";


// 2. VÉRIFIER LA SYNTAXE DE L'EMAIL

if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {

    echo "Syntaxe incorrecte : adresse email invalide.";
    exit;
}


// 3. RÉCUPÉRER LE DOMAINE

$parties = explode("@", $destinataire);

$domaine = $parties[1];


// 4. VÉRIFIER QUE LE NOM DU DOMAINE CONTIENT DES LETTRES

$nomDomaine = explode(".", $domaine)[0];

if (!preg_match('/[a-zA-Z]/', $nomDomaine)) {

    echo "Domaine invalide.";
    exit;
}


// 5. VÉRIFIER QUE LE DOMAINE EXISTE

if (!checkdnsrr($domaine, "A")) {

    echo "Domaine invalide ou inexistant.";
    exit;
}


// 6. VÉRIFIER LES FICHIERS

if (empty($fichiers)) {

    echo "Veuillez sélectionner au moins un fichier.";
    exit;
}


// 7. CRÉER PHPMailer

$mail = new PHPMailer();


// 8. CONFIGURER SMTP GMAIL

$mail->isSMTP();

$mail->Host = 'smtp.gmail.com';

$mail->SMTPAuth = true;

$mail->Username = 'TON_EMAIL@gmail.com';

$mail->Password = 'TON_APP_PASSWORD';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

$mail->Port = 587;


// 9. DÉFINIR L'EXPÉDITEUR

$mail->setFrom(
    'TON_EMAIL@gmail.com',
    'Gestion des emails'
);


// 10. DÉFINIR LE DESTINATAIRE

$mail->addAddress($destinataire);


// 11. DÉFINIR LE SUJET

$mail->Subject = 'Fichiers générés';


// 12. DÉFINIR LE MESSAGE

$mail->Body = 'Bonjour, veuillez trouver les fichiers en pièces jointes.';


// 13. AJOUTER LES FICHIERS

foreach ($fichiers as $fichier) {

    $mail->addAttachment($fichier);
}


// 14. ENVOYER L'EMAIL

try {

    $mail->send();

    echo "<h2>Email envoyé avec succès !</h2>";

} catch (Exception $e) {

    echo "<h2>Erreur lors de l'envoi :</h2>";

    echo $mail->ErrorInfo;
}

?>