<?php namespace Engine\Mail;

class Simple extends Mail
{
    public function send()
    {
        $to = $this->toString(self::MAIL_TO);
        if (empty ($to)) {
            return false;
        }

        $cc      = $this->toString(self::MAIL_CC);
        $bcc     = $this->toString(self::MAIL_BCC);
        $from    = $this->toString(self::MAIL_FROM);
        $replyTo = $this->toString(self::MAIL_REPLY_TO);
        $subject = $this->subject;

        if (!empty($from)) {
            $this->addHeader('From', $from);
        } else {
            $this->addHeader('From', 'nobody@domain.com');
        }
        if (!empty($replyTo)) {
            $this->addHeader('Reply-To', $replyTo);
        }
        if (!empty($cc)) {
            $this->addHeader('Cc', $cc);
        }
        if (!empty($bcc)) {
            $this->addHeader('Bcc', $bcc);
        }

        $header = $this->toString(self::MAIL_HEADER);

        return mail($to, $subject, $this->buildBody(), $header);
    }
}
