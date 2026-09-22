<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


// 1. Récupérer les données du formulaire

$destinataires = $_POST["destinataires"] ?? [];
$objet = $_POST["objet"] ?? "";
$message = $_POST["message"] ?? "";


// 2. Vérifier les destinataires

if (empty($destinataires)) {

    echo "Veuillez sélectionner au moins une adresse email.";
    exit;
}


// 3. Vérifier les adresses email

foreach ($destinataires as $destinataire) {

    if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {

        echo "Adresse email invalide : "
            . htmlspecialchars($destinataire);

        exit;
    }
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
    'Gestion-emails'
);


// 7. Ajouter les destinataires

foreach ($destinataires as $destinataire) {

    $mail->addAddress($destinataire);
}


// 8. Définir l'objet

$mail->Subject = $objet;


// 9. Définir le message

$mail->Body = $message;


// 10. Ajouter la pièce jointe si elle existe

if (
    isset($_FILES["piece_jointe"])
    && $_FILES["piece_jointe"]["error"] === 0
) {

    $fichier = $_FILES["piece_jointe"]["tmp_name"];

    $nomFichier = $_FILES["piece_jointe"]["name"];

    $mail->addAttachment(
        $fichier,
        $nomFichier
    );
}


// 11. Envoyer le message

try {

    $mail->send();

    echo "<h2>Message envoyé avec succès !</h2>";

} catch (Exception $e) {

    echo "<h2>Erreur lors de l'envoi :</h2>";

    echo $mail->ErrorInfo;
}

?>