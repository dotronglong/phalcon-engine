<?php namespace Engine\Tests\Mail;

use Engine\Mail\Contract as Mail;

trait ContractTest
{
    /**
     * @depends testImplementContract
     */
    public function testAddErrors(Mail $mailer)
    {
        $mailer = clone($mailer);
        $mailer->addError('this_is_an_error');
        $this->assertCount(1, $mailer->getErrors());
    }
    
    /**
     * @depends testImplementContract
     */
    public function testGetErrors(Mail $mailer)
    {
        $mailer = clone($mailer);
        $this->assertEquals([], $mailer->getErrors());
        $this->assertEquals('', $mailer->getErrors(true));
    }
    
    /**
     * @depends testImplementContract
     */
    public function testRemoveErrors(Mail $mailer)
    {
        $mailer = clone($mailer);
        $mailer->addError('this_is_an_error');
        $this->assertCount(1, $mailer->getErrors());
        $mailer->removeErrors();
        $this->assertCount(0, $mailer->getErrors());
    }
    
    /**
     * @depends testImplementContract
     */
    public function testSetGetConfg(Mail $mailer)
    {
        $mailer = clone($mailer);
        $mailer->setConfig('var', 'value');
        $this->assertEquals('value', $mailer->getConfig('var'));
        $mailer->setConfigArray(['my' => 'config']);
        $this->assertEquals('config', $mailer->getConfig('my'));
        $mailer->setConfigArray(['my' => 'config'], false);
        $this->assertNull($mailer->getConfig('var'));
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddToMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addTo', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddCcMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addCc', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddBccMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addBcc', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddFromMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addFrom', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddReplyToMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addReplyTo', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasAddAttachmentMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('addAttachment', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHasSetGetMethod(Mail $mailer)
    {
        $this->assertClassHasMethod('__isset', $mailer);
        $this->assertClassHasMethod('__get', $mailer);
    }
    
    /**
     * @depends testImplementContract
     */
    public function testHeader(Mail $mailer)
    {
        $mailer = clone($mailer);
        $mailer->addHeader('my_header', 'my_value');
        $this->assertEquals(['my_header' => 'my_value'], $mailer->{Mail::MAIL_HEADER});
        $mailer->removeHeader('my_header');
        $this->assertCount(0, $mailer->{Mail::MAIL_HEADER});
        $mailer->addHeader('my_header', 'my_value');
        $this->assertTrue($mailer->hasHeader('my_header'));
        $mailer->removeHeaders();
        $this->assertCount(0, $mailer->{Mail::MAIL_HEADER});
    }
    
    /**
     * @depends testImplementContract
     */
    public function testCharset(Mail $mailer)
    {
        $mailer = clone($mailer);
        $this->assertNull($mailer->{Mail::MAIL_CHARSET});
        $mailer->setCharset('utf-8');
        $this->assertEquals('utf-8', $mailer->{Mail::MAIL_CHARSET});
    }
    
    /**
     * @depends testImplementContract
     */
    public function testSubject(Mail $mailer)
    {
        $mailer = clone($mailer);
        $this->assertNull($mailer->{Mail::MAIL_SUBJECT});
        $mailer->setSubect('my_subject');
        $this->assertEquals('my_subject', $mailer->{Mail::MAIL_SUBJECT});
    }
    
    /**
     * @depends testImplementContract
     */
    public function testBody(Mail $mailer)
    {
        $mailer = clone($mailer);
        $this->assertNull($mailer->{Mail::MAIL_BODY_PLAIN});
        $mailer->setBodyPlainText('my_body');
        $this->assertEquals('my_body', $mailer->{Mail::MAIL_BODY_PLAIN});
    }
    
    /**
     * @depends testImplementContract
     */
    public function testBodyHtml(Mail $mailer)
    {
        $mailer = clone($mailer);
        $this->assertNull($mailer->{Mail::MAIL_BODY_HTML});
        $mailer->setBodyHtml('<body>my html body</body>');
        $this->assertEquals('<body>my html body</body>', $mailer->{Mail::MAIL_BODY_HTML});
    }
}