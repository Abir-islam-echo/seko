<?php

namespace Toll_Integration;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    protected $mailer;
    protected $db;
    protected $api;
    protected $log;
    protected $mail;
    public $status;


    public function __construct()
    {
        $this->db = new DB();
        $this->log = new Log();
        $this->api = new API();
        $this->status = false;
        $this->mail = new PHPMailer(true);
        try {
            //Server settings
            $this->mail->SMTPDebug  = SMTP::DEBUG_OFF;
            $this->mail->isSMTP();
            $this->mail->Host       = $this->db->getConfig('mail_host', 'mail');
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->db->getConfig('mail_username', 'mail');;
            $this->mail->Password   = $this->db->decryption($this->db->getConfig('mail_password', 'mail'));
            $this->mail->SMTPSecure = $this->db->getConfig('mail_encryption', 'mail');
            $this->mail->Port       = $this->db->getConfig('mail_port', 'mail');
            if ($this->mail->smtpConnect()) {
                $this->mail->smtpClose();
                $this->status = true;
            }
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $this->status = false;
        }
    }

    public function sendMail($subject, $body, $orderData)
    {
        try {
            $this->mail->setFrom($this->db->getConfig('mail_settings_mailer', 'mail_settings'), $this->db->getConfig('mail_settings_name', 'mail_settings'));
            $this->mail->addAddress($this->db->getConfig('mail_settings_recipient', 'mail_settings'));
            $this->mail->addCC($this->db->getConfig('mail_settings_cc', 'mail_settings'));
            $this->mail->addBCC($this->db->getConfig('mail_settings_bcc', 'mail_settings'));

            //Content
            $this->mail->isHTML(true); //Set email format to HTML  
            $body = $this->mailBody($body, $orderData);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;


            $this->mail->send();
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $this->status = false;
        }
    }
    public function mailBody($body, $orderData)
    {
        ob_start();
        include APP_DIR . "/public/view/mail_template.php";
        $body = ob_get_clean();
        return $body;
    }
}
