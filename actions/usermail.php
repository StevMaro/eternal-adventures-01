<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'booking/vendor/autoload.php'; // Adjust this path to where PHPMailer is located

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com'; // Hostinger's SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eternal-adventures.com'; // Your email
    $mail->Password = 'Noreply@2024'; // Email password, ensure this is correct
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
    $mail->Port = 465;

    // Sender and Recipient
    $mail->setFrom('no-reply@eternal-adventures.com', 'Eternal Adventures');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
$mail->Subject = 'Inquiry Received - Eternal Adventures';
$mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif; margin: 0; padding: 0;'>
        <div style='max-width: 600px; margin: 20px auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; border: 1px solid #ddd;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <img src='https://eternal-adventures.com/assets/images/r.png' alt='Eternal Adventures Logo' style='max-width: 150px;'>
            </div>
            <h2 style='color: #2b6cb0; text-align: center;'>Inquiry Received</h2>
            <p>Hi <strong>{$name}</strong>,</p>
            <p>Thank you for contacting Eternal Adventures. We have received your inquiry and will get back to you within <strong>24 hours</strong>.</p>
            <p>Here are the details of your inquiry:</p>
            <table style='width: 100%; border-collapse: collapse; background-color: #e6f7ff; padding: 10px; border-radius: 5px;'>
                <tr>
                    <th style='text-align: left; border-bottom: 1px solid #ddd; padding: 8px;'>Name</th>
                    <td style='border-bottom: 1px solid #ddd; padding: 8px; color: #2b6cb0;'>{$name}</td>
                </tr>
                <tr>
                    <th style='text-align: left; border-bottom: 1px solid #ddd; padding: 8px;'>Email</th>
                    <td style='border-bottom: 1px solid #ddd; padding: 8px; color: #2b6cb0;'>{$email}</td>
                </tr>
                <tr>
                    <th style='text-align: left; border-bottom: 1px solid #ddd; padding: 8px;'>Message</th>
                    <td style='border-bottom: 1px solid #ddd; padding: 8px; color: #2b6cb0;'>{$message}</td>
                </tr>
            </table>
            <p style='font-size: small; margin-top: 20px;'>
                If you have any further questions, feel free to contact us at <br>
                <a href='mailto:support@eternal-adventures.com' style='color: #2b6cb0; text-decoration: none;'>support@eternal-adventures.com</a>.
            </p>
            <p style='text-align: center; color: #888; font-weight: 600; margin-top: 20px;'>Best regards,<br>The Eternal Adventures Team</p>
        </div>
    </body>
    </html>
";
$mail->AltBody = "Inquiry Received - Hi {$name},\n\nThank you for contacting Eternal Adventures. We have received your inquiry and will get back to you within 24 hours.\n\nInquiry Details:\nName: {$name}\nEmail: {$email}\nMessage: {$message}\n\nBest regards,\nThe Eternal Adventures Team";
    // Send Email
    $mail->send();
    $smg = "Confirmation email sent successfully.";
} catch (Exception $e) {
    error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    echo "There was an issue sending the confirmation email: " . $mail->ErrorInfo;
}