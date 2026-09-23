<?php

// Vérifier que le formulaire vient bien de index.php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}



// 1. RÉCUPÉRER L'EMAIL


$email = trim($_POST["email"] ?? "");



// 2. VALIDATION CÔTÉ SERVEUR


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: index.php?ajout=invalid");

    exit;
}


//  VÉRIFIER QUE LE DOMAINE EXISTE


$parties = explode("@", $email);
$domaine = $parties[1];

if (!checkdnsrr($domaine, "A")) {

    header("Location: index.php?ajout=domaine_invalide");

    exit;
}



// 3. VÉRIFIER QUE emails.txt EXISTE


if (!file_exists("emails.txt")) {

    file_put_contents("emails.txt", "");
}


// ==========================================
// 4. LIRE LES EMAILS EXISTANTS
// ==========================================

$emails = file(
    "emails.txt",
    FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
);


// ==========================================
// 5. VÉRIFIER SI L'EMAIL EXISTE DÉJÀ
// ==========================================

if (in_array($email, $emails)) {

    header("Location: index.php?ajout=existe");

    exit;
}


// ==========================================
// 6. AJOUTER L'EMAIL DANS emails.txt
// ==========================================

file_put_contents(
    "emails.txt",
    $email . PHP_EOL,
    FILE_APPEND
);


// ==========================================
// 7. RELANCER LE TRAITEMENT
// ==========================================

ob_start();

include "traitement.php";

ob_end_clean();


// ==========================================
// 8. RETOURNER VERS index.php
// ==========================================

header("Location: index.php?ajout=success");
exit;
?>