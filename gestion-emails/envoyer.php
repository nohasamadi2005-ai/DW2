<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


// 1. Récupérer les données

$destinataire = $_POST["destinataire"];

$fichiers = $_POST["fichiers"];


// 2. Vérifier l'adresse email

if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {

    echo "Syntaxe incorrecte : adresse email invalide.";
    exit;
}


// 3. Vérifier les fichiers

if (empty($fichiers)) {

    echo "Veuillez sélectionner au moins un fichier.";
    exit;
}


// 4. Créer PHPMailer

$mail = new PHPMailer();


// 5. Configurer SMTP Gmail

$mail->isSMTP();

$mail->Host = 'smtp.gmail.com';

$mail->SMTPAuth = true;

$mail->Username = 'rayaakouibel@gmail.com';

$mail->Password = 'cskm djpd nmwz hbcu';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

$mail->Port = 587;


// 6. Définir l'expéditeur

$mail->setFrom(
    'rayaakouibel@gmail.com',
    'Gestion des emails'
);


// 7. Définir le destinataire

$mail->addAddress($destinataire);


// 8. Définir le sujet

$mail->Subject = 'Fichiers générés';


// 9. Définir le message

$mail->Body = 'Bonjour, veuillez trouver les fichiers en pièces jointes.';


// 10. Ajouter les fichiers sélectionnés comme pièces jointes

foreach ($fichiers as $fichier) {

    $mail->addAttachment($fichier);
}

try {

    $mail->send();

    echo "<h2>Email envoyé avec succès !</h2>";

} catch (Exception $e) {

    echo "<h2>Erreur lors de l'envoi :</h2>";
    echo $mail->ErrorInfo;

}

?>