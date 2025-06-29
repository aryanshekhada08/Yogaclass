<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendConfirmationMail($to_email, $to_name, $subject, $bodyHtml) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aryanshekhada30@gmail.com'; // Replace with your Gmail
        $mail->Password   = 'nsql ucnd hthr kywe';    // Use App Password (not your login password)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('aryanshekhada30@gmail.com', 'Yoga Class');
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
function sendEmailWithAttachment($to_email, $subject, $bodyText, $attachmentPath) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aryanshekhada30@gmail.com'; // Your Gmail
        $mail->Password   = 'nsql ucnd hthr kywe';       // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('aryanshekhada30@gmail.com', 'Yoga Class');
        $mail->addAddress($to_email);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($bodyText); // Converts newlines to <br> for HTML
        $mail->isHTML(true);

        // Attach the invoice PDF
        if (file_exists($attachmentPath)) {
            $mail->addAttachment($attachmentPath);
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
