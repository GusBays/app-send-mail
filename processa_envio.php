<?php
    require './libs/PHPMailer/Exception.php';
    require './libs/PHPMailer/PHPMailer.php';
    require './libs/PHPMailer/OAuth.php';
    require './libs/PHPMailer/SMTP.php';
    require './libs/PHPMailer/POP3.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $value) {
            $this->$attr = $value;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    //print_r($mensagem);

    if(!$mensagem->mensagemValida()) {
        echo 'Mensagem não é válida';
        die();
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 2;                                       //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'phpsendmail7.4@gmail.com';                     //SMTP username
        $mail->Password   = '1234@abc';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('phpsendmail7.4@gmail.com', 'PHP Send Mail App Sender');
        $mail->addAddress('phpsendmail7.4@gmail.com', 'PHP Send Mail App Receiver');     //Add a recipient           //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');         //Add another address if the receiver reply the message
        //$mail->addCC('cc@example.com');                             //Add another e-mail for copy
        //$mail->addBCC('bcc@example.com');                           //Add another e-mail as a hiden copy

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Assunto do email';
        $mail->Body    = 'Este é um teste de corpo do <strong>email</strong>';
        $mail->AltBody = 'Este é um teste de corpo do email sem tags HTML';

        $mail->send();
        echo 'Mensagem enviada com sucesso';
    } catch (Exception $e) {
        echo "Não foi possível enviar este email. Erro encontrado: {$mail->ErrorInfo}";
    }



