<?php

require '../PHPMailer-7.1.1/src/Exception.php';
require '../PHPMailer-7.1.1/src/PHPMailer.php';
require '../PHPMailer-7.1.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'aceaviation2026@gmail.com';
$mail->Password = 'YOUR_APP_PASSWORD';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('aceaviation2026@gmail.com');
$mail->addAddress('aceaviation2026@gmail.com');

$mail->Subject = 'Test Mail';
$mail->Body = 'Working';

if($mail->send()){
    echo "MAIL SENT";
}else{
    echo "FAILED";
}