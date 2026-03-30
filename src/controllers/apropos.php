<?php
require_once './vendor/PHPMailer/src/Exception.php';
require_once './vendor/PHPMailer/src/PHPMailer.php';
require_once './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";

$aproposRouter = new Router();
$aproposRouter->get("/apropos/decouvrir-yitro", function () {
    TemplateRender::render("/aPropos/decouvrirYitro.php", null);
});

$aproposRouter->get("/apropos/faq", function () {
    TemplateRender::render("/aPropos/faq.php", null);
});

$aproposRouter->get("/apropos/contact", function () {
    TemplateRender::render("/aPropos/contact.php", null);
});

$aproposRouter->post("/apropos/contact", function () {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $sujet = $_POST["sujet"];
    $message = $_POST["message"];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'edwinomaminirina@gmail.com'; // Remplacez par votre adresse Gmail
    $mail->Password = ''; // Remplacez par le mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Désactiver le débogage
    $mail->SMTPDebug = 0; // 0 = désactivé
    $mail->Debugoutput = 'html'; // Sortie par défaut (non utilisée si SMTPDebug = 0)

    // Destinataire
    $mail->setFrom($email, 'Yitro Learning');
    $mail->addAddress('edwinomaminirina@gmail.com');

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = $sujet;
    $mail->Body = $message;

    $mail->send();
    
    header("Location: /apropos/contact");
    exit;
});
