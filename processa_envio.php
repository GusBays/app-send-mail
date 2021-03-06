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
        public $status = ['codigo_status' => null, 'descricao_status' => ''];

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
        header('Location: index.php');
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                                       //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'phpsendmail7.4@gmail.com';                     //SMTP username
        $mail->Password   = '1234@abc';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('phpsendmail7.4@gmail.com', 'PHP Send Mail App');
        $mail->addAddress($mensagem->__get('para'));                  //Add a recipient           //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');       //Add another address if the receiver reply the message
        //$mail->addCC('cc@example.com');                             //Add another e-mail for copy
        //$mail->addBCC('bcc@example.com');                           //Add another e-mail as a hiden copy

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = '?? necess??rio que voc?? utilize um client que suporte HTML para visualizar todo o conte??do desta mensagem.';

        $mail->send();
        $mensagem->status['codigo_status'] = 200;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';

    } catch (Exception $e) {
        $mensagem->status['codigo_status'] = 500;
        $mensagem->status['descricao_status'] = "N??o foi poss??vel enviar este email. Erro encontrado: {$mail->ErrorInfo}";

    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
        <title>App Send Mail</title>
    </head>
    <body>
        <div class="container">
            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
                <h2>Send Mail</h2>
                <p class="lead">Seu app de envio de e-mails particular!</p>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <?php if($mensagem->status['codigo_status'] == 200) { ?>

                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?>

                    <?php if($mensagem->status['codigo_status'] == 500) { ?>

                        <div class="container">
                            <h1 class="display-4 text-danger">Erro</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>
                        
                     <?php } ?>
                    

                </div>
            </div>
        </div>
    </body>
</html>