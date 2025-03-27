<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'booking/vendor/autoload.php'; // Adjust this path to where PHPMailer is located

try {
    // SMTP Configuration for Admin Notification
    $mailAdmin = new PHPMailer(true);
    $mailAdmin->isSMTP();
    $mailAdmin->Host = 'smtp.hostinger.com'; // Hostinger SMTP server
    $mailAdmin->SMTPAuth = true;
    $mailAdmin->Username = 'no-reply@eternal-adventures.com'; // Your email
    $mailAdmin->Password = 'Noreply@2024'; // Your email password
    $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
    $mailAdmin->Port = 465;

    // Sender and Recipient
    $mailAdmin->setFrom('no-reply@eternal-adventures.com', 'Eternal Adventures');
    $mailAdmin->addAddress('info@eternal-adventures.com', 'Eternal Adventures Admin');

    // Email Content for Admin
    $mailAdmin->isHTML(true);
    $mailAdmin->Subject = 'New Inquiry Alert - Eternal Adventures';
    $mailAdmin->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; margin: 0; padding: 0;'>
            <div style='max-width: 600px; margin: 20px auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; border: 1px solid #ddd;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='https://eternal-adventures.com/assets/images/r.png' alt='Eternal Adventures Logo' style='max-width: 150px;'>
                </div>
                <h2 style='color: #2b6cb0; text-align: center;'>New Inquiry Alert</h2>
                <p>A new inquiry has been submitted on Eternal Adventures. Below are the details:</p>
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
                <p style='margin-top: 20px;'>Please visit the <a href='https://admin.eternal-adventures.com' style='color: #2b6cb0; text-decoration: none;'>Admin Panel</a> to view more details about this inquiry.</p>
                <p style='text-align: center; color: #888; font-weight: 600;'>Eternal Adventures System</p>
            </div>
        </body>
        </html>
    ";
    $mailAdmin->AltBody = "New Inquiry Alert - A new inquiry has been submitted.\n\nName: {$name}\nEmail: {$email}\nMessage: {$message}\n\nVisit the Admin Panel: https://admin.eternal-adventures.com";
    // Send Admin Notification Email
    $mailAdmin->send();
    $adminmasg = "Admin notification email sent successfully.";
} catch (Exception $e) {
    error_log("Admin notification email could not be sent. Error: {$mailAdmin->ErrorInfo}");
    echo "There was an issue sending the admin notification email.";
}
