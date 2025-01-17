<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'dbconnect.php'; 
require 'PHPMailer/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM clients WHERE Email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($Password, $user['Password'])) {
            $VerificationCode = rand(1000, 9999);

            $_SESSION['VerificationCode'] = $VerificationCode;
            $_SESSION['Email'] = $Email;

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'nicole.wachira2@gmail.com'; 
                $mail->Password = 'ybcahlwsyjoolugj';   
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Correct encryption
                $mail->Port = 465; // Correct port

                // Recipients
                $mail->setFrom('nicole.wachira@strathmore.edu', 'CYCLA SYSTEMS'); 
                $mail->addAddress($Email, $user['Username']); 

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your Verification Code';
                $mail->Body    = "<p>Dear {$user['Username']},</p>
                                  <p>Your verification code is: <strong>{$VerificationCode}</strong></p>
                                  <p>Enter this code on the verification page to access your account.</p>";

                $mail->AltBody = "Dear {$user['Username']},\nYour verification code is: {$VerificationCode}";

                $mail->send();

                // Redirect to verify.php
                header('Location: verify.php');
                exit();
            } catch (Exception $e) {
                echo "Error sending email: {$mail->ErrorInfo}";
            }
        } else {
            echo "<div class='container mt-5'><div class='alert alert-danger text-center'>Invalid email or password.</div></div>";
        }
    } catch (PDOException $e) {
        echo "<div class='container mt-5'><div class='alert alert-danger text-center'>Database error: " . $e->getMessage() . "</div></div>";
<<<<<<< HEAD
    }

}
=======
    }
}
?>
>>>>>>> 376c4d609aea612105bdfbc747cd85d970aad379
