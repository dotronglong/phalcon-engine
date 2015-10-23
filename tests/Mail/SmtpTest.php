<?php namespace Engine\Tests\Mail;

use Engine\Tests\TestCase;
use Engine\Mail\Contract as MailContract;
use Engine\Mail\Smtp as Mail;

class SmtpTest extends TestCase
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
//        $mailer->setConfigArray([
//            'encrypt' => Mail::ENCRYPT_SSL,
//            'host' => '',
//            'port' => 465,
//            'user' => '',
//            'pwd'  => ''
//        ]);
//        $result = $mailer->addTo('long@get4x.co')
//               ->setSubect('test')
//               ->setBodyPlainText('test body')
//               ->setBodyHtml('<span>test body html</span>')
//               ->send();
//        $this->assertTrue($result);
    }
}