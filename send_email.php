<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer
require 'vendor/autoload.php';
include 'config/db.php'; // Your DB connection file

// Input validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$message) {
        echo "All fields are required and email must be valid.";
        exit;
    }

    // Save to database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    if (!$stmt->execute()) {
        echo "Error saving message. Please try again.";
        exit;
    }

    // Send email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';  // ✅ Replace with your SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'you@example.com';   // ✅ Your SMTP username
        $mail->Password   = 'yourpassword';      // ✅ Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'Portfolio Website');
        $mail->addAddress('yourname@example.com', 'Your Name'); // ✅ Your actual email

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Portfolio Contact From $name";
        $mail->Body    = "
            <h3>You received a new message</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";

        $mail->send();
        echo "✅ Your message has been sent successfully.";
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
