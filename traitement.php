<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$smtp_email = "exemple@outlook.fr"; // Email qui recevra le mail
$smtp_pass = "Passw0rd"; // Mot de passe  de l'email qui reçoit
$smtp_host = "smtp-mail.outlook.com"; // Remplacez par l'adresse du serveur SMTP (Celui ci fonctionne que pour les adresses outlook)
$smtp_port = 587; // Le port SMTP à utiliser

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    $email_content = "Nom: $nom\n\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_email; 
        $mail->Password   = $smtp_pass;          
        $mail->SMTPSecure = 'tls';
        $mail->Port       = $smtp_port;

        $mail->setFrom($smtp_email, "Formulaire de contact");
        $mail->addAddress($smtp_email); 

        // Cette ligne permet d'éviter les injections et tranforme le html en texte
        $mail->isHTML(false);
        $mail->Subject = "Contact from $email";
        $mail->Body    = $email_content;

        $mail->send();
        echo 'Mail envoyé';
    } catch (Exception $e) {
        echo "Le mail n'a pas pu être envoyé : {$mail->ErrorInfo}";
    }
}
?>