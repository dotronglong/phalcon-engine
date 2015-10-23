<?php namespace Engine\Tests\Mail;

use Engine\Tests\TestCase;
use Engine\Mail\Contract as MailContract;
use Engine\Mail\Simple as Mail;

class SimpleTest extends TestCase
{
    use ContractTest;
    
    public function testImplementContract()
    {
        $mail = new Mail();
        $this->assertInstanceOf(MailContract::class, $mail);
        return $mail;
    }
    
    /**
     * @depends testImplementContract
     */
    public function testSendEmail(Mail $mailer)
    {
//        $mailer = clone($mailer);
//        $result = $mailer->addTo('long@get4x.co')
//               ->setSubect('test')
//               ->setBodyPlainText('test body')
//               ->setBodyHtml('<span>test body html</span>')
//               ->send();
//        if (!empty(ini_get('sendmail_path'))) {
//            $this->assertTrue($result);
//        }
    }
}