<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-7.1.1/src/Exception.php';
require '../PHPMailer-7.1.1/src/PHPMailer.php';
require '../PHPMailer-7.1.1/src/SMTP.php';

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$education = trim($data['education'] ?? '');
$message = trim($data['message'] ?? '');

// --- FIX FOR MULTIPLE SELECT ARRAY ---
$interest_data = $data['interest'] ?? '';
if (is_array($interest_data)) {
    // Convert array ['School Selection', 'Financial Planning'] into "School Selection, Financial Planning"
    $interest = implode(', ', $interest_data);
} else {
    $interest = trim($interest_data);
}
// -------------------------------------

if ($name == '' || $email == '' || $phone == '') {
    echo json_encode([
        "success" => false,
        "message" => "Required fields missing"
    ]);
    exit();
}

$sql = "INSERT INTO enquiries (name, email, phone, education, interest, message) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $phone, $education, $interest, $message);

if ($stmt->execute()) {

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aceaviation2026@gmail.com';
        $mail->Password = 'ogrrsjztrhpsviyb'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
// 1. Keep the From address as your authenticated Gmail, but put their Name
$mail->setFrom('aceaviation2026@gmail.com', $name);

// 2. Keep the Reply-To so clicking 'Reply' goes to the right person
$mail->addReplyTo($email, $name); 

$mail->addAddress('aceaviation2026@gmail.com');

// 3. ALTERNATIVE FIX: Put the sender's email directly into the Subject
// This makes it visible instantly in your inbox list view!
$mail->Subject = "New Lead: " . $name . " (" . $email . ")";

// 4. ALTERNATIVE FIX: Prepend the sender's details clearly at the top of the body
$email_body = "<h3>Form Submission Details:</h3>";
$email_body .= "<b>Sender Name:</b> " . $name . "<br>";
$email_body .= "<b>Sender Email:</b> " . $email . "<br>";
$email_body .= "<hr>"; // Line break
$email_body .= "<b>Message:</b><br>" . $message; // Your actual message content

$mail->isHTML(true);
$mail->Body = $email_body;

        $mail->isHTML(true);
        $mail->Subject = 'New Consultation Request - The Ace Aviator';
        $mail->Body = "
        <h2 style='background:#c49440;color:#fff;padding:15px;text-align:center;margin:0;'>
            New Consultation Request
        </h2>
        <table style='width:100%;border-collapse:collapse;font-family:Arial,sans-serif;border:1px solid #dcdcdc;'>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;width:30%;'>Full Name</td>
                <td style='padding:12px;border:1px solid #ddd;'>$name</td>
            </tr>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;'>Email Address</td>
                <td style='padding:12px;border:1px solid #ddd;'>$email</td>
            </tr>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;'>Phone Number</td>
                <td style='padding:12px;border:1px solid #ddd;'>$phone</td>
            </tr>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;'>Education</td>
                <td style='padding:12px;border:1px solid #ddd;'>$education</td>
            </tr>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;'>Area of Interest</td>
                <td style='padding:12px;border:1px solid #ddd;'>$interest</td>
            </tr>
            <tr>
                <td style='background:#f5f5f5;padding:12px;font-weight:bold;color:#0e273d;border:1px solid #ddd;vertical-align:top;'>Message</td>
                <td style='padding:12px;border:1px solid #ddd;'>$message</td>
            </tr>
        </table>
        <br>
        <div style='background:#faf7ef;border-left:4px solid #c49440;padding:12px;color:#555;font-size:14px;'>
            This enquiry was submitted from <b>The Ace Aviator</b> website.
        </div>
        ";

        $mail->send();

    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
    }

    echo json_encode([
        "success" => true
    ]);

} else {
    echo json_encode([
        "success" => false,
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>