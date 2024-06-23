<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('./Models/Email.php');

class EmailController
{
    private $emailModel;

    public function __construct($pdo)
    {
        $this->emailModel = new Email($pdo);
    }

    public function getAllEmails()
    {
        try {
            $response = $this->emailModel->getAllEmails();

            http_response_code(200);
            echo json_encode($response);
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
            echo json_encode($response);
        }
    }

    private function createEmail($data)
    {
        if (!empty($data['userId']) && !empty($data['recipient']) && !empty($data['recipientName'] && !empty($data['subject']) && !empty($data['body']))) {
            try {
                $response = $this->emailModel->createEmail($data);

                http_response_code(200);
                echo json_encode($response);
            } catch (PDOException $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
                echo json_encode($response);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error']);
        }
    }

    public function getUserEmail($userId){
        try {
            $response = $this->emailModel->getEmailByUserid($userId);

            http_response_code(200);
            echo json_encode($response);
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
            echo json_encode($response);
        }
    }

    public function sendEmail($data, $user)
    {
        // Load Composer's autoloader
        require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USERNAME'];
            $mail->Password = $_ENV['EMAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($user['email'], $user['fullName']);
            $mail->addAddress($data['recipient'], $data['recipientName']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body = $data['body'];

            $mail->send();
            $data['userId'] = $user['id'];
            $this->createEmail($data);
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}