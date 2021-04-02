<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Only process POST reqeusts.
if (isset($_POST['email'])) {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);
    // Instantiation and passing `true` enables exceptions
    
    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";
    
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        
        // $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'dswd.pupdiuyanmoderator@gmail.com';                     // SMTP username
        $mail->Password   = 'trackingsystem09';                               // SMTP password
        $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;  
        
        //Recipients
        $mail->isHTML(true);
        $mail->setFrom($email, $name);
        $mail->addAddress($mail->Username);
        $mail->Subject = ("$email ($subject)");
        $mail->Body = $message;
        // $mail->send();
        if($mail->send()){
            $status = "success";
            $response = "Message has been sent.";
        }
        else
        {
            $status = "failed";
            $response = "Something is wrong: <br>" . $mail->ErrorInfo;
        }
    
        exit(json_encode(array("status" => $status, "response" => $response)));
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
