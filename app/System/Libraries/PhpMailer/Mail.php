<?php
/**
 * Mail Helper
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.0
 */

namespace Libs\PhpMailer;

/**
 * Custom class for PHPMailer to uniform sending emails.
 */
class Mail extends PhpMailer
{
    /**
     * From
     *
     * @var string $From set sender email
     */
    public $From     = EMAIL_USERNAME;

    /**
     * FromName
     *
     * @var string $FromName set sender name
     */
    public $FromName = EMAIL_FROM_NAME;

    /**
     * Host
     *
     * @var string $Host set sender SMTP Route
     */
    public $Host     = EMAIL_HOST;

    /**
     * Mailer
     *
     * @var string $Mailer set type default is SMTP
     */
    public $Mailer   = 'smtp';

    /**
     * SMTPAuth
     *
     * @var string $SMTPAuth use authenticated
     */
    public $SMTPAuth = true;

    /**
     * Username
     *
     * @var string $Username set username
     */
    public $Username = EMAIL_USERNAME;

    /**
     * Password
     *
     * @var string $Password set password
     */
    public $Password = EMAIL_PASSWORD;

    /**
     * SMTPSecure
     *
     * @var string $SMTPSecure set Secure SMTP
     */
    public $SMTPSecure = EMAIL_STMP_SECURE;

    /**
     * The default SMTP server port.
     * @type int
     */
    public $Port = EMAIL_PORT;

    /**
     * WordWrap
     * @var integer $WordWrap set word wrap
     */
    public $WordWrap = 75;

    /**
     * Subject
     *
     * @param  string $subject The subject of the email
     */
    public function subject($subject)
    {
        $this->Subject = $subject;
    }

    /**
     * Body
     *
     * @param  string $body The content of the email
     */
    public function body($body)
    {
        $this->Body = $body;
    }

    /**
     * Send
     *
     * @return none - sends the email.
     */
    public function send()
    {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }

    /**
     * Clean Message.
     * Remove email specific characters from a given string
     *
     * @param string. Email body.
     * @return string.
     */
    public function cleanMessage($string)
    {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad, "", $string);
    }
}
