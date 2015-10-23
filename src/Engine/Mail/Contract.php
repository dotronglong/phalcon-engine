<?php namespace Engine\Mail;

interface Contract
{
    const ENCRYPT_NONE  = NULL;
    const ENCRYPT_SSL   = 'ssl';
    const ENCRYPT_TLS   = 'tls';
    const TRANSPORT_TCP = 'tcp';
    const TRANSPORT_UDP = 'udp';
    
    const MAIL_TO         = 'to';
    const MAIL_CC         = 'cc';
    const MAIL_BCC        = 'bcc';
    const MAIL_FROM       = 'from';
    const MAIL_REPLY_TO   = 'reply_to';
    const MAIL_SUBJECT    = 'subject';
    const MAIL_HEADER     = 'header';
    const MAIL_CHARSET    = 'charset';
    const MAIL_BODY_HTML  = 'body_html';
    const MAIL_BODY_PLAIN = 'body_plain';
    const MAIL_ATTACHMENT = 'attachment';

    const ATTACHMENT_FILE    = 1;
    const ATTACHMENT_CONTENT = 2;
    
    /**
     * Add error
     * 
     * @param string $message
     * @param string $err_str
     * @param string|int $err_no
     * @return static
     */
    public function addError($message, $err_str = null, $err_no = null);
    
    /**
     * Get errors
     * @param bool $toString return string instead of array
     * @return string|array
     */
    public function getErrors($toString = false);
    
    /**
     * Remove all errors
     * 
     * @return static
     */
    public function removeErrors();
    
    /**
     * Get / Set Configuration
     *
     * <code>
     * $mail->setConfig('host', 'host_name'); // set configuration by name
     * </code>
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function setConfig($name = null, $value = null);
    
    /**
     * Get configuration
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getConfig($name = null, $default = null);
    
    /**
     * Set config
     * 
     * <code>
     * $mail->setConfigArray([
     *     'host' => 'host_name',
     *     'user' => 'user_name',
     *     [...]
     * ], false); // set and replace the current configuration
     * </code>
     * 
     * @param array $config
     * @param bool $merge
     * @return static
     */
    public function setConfigArray($config, $merge = true);
    
    /**
     * Add an email address to TO section
     *
     * <code>
     * $mailer->addTo('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return static
     */
    public function addTo($email, $name = '');
    
    /**
     * Add an email address to CC section
     *
     * <code>
     * $mailer->addCc('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return static
     */
    public function addCc($email, $name = '');
    
    /**
     * Add an email address to BCC section
     *
     * <code>
     * $mailer->addBcc('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return static
     */
    public function addBcc($email, $name = '');
    
    /**
     * Add an email address to FROM section
     *
     * <code>
     * $mailer->addFrom('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return static
     */
    public function addFrom($email, $name = '');
    
    /**
     * Add an email address to REPLY-TO section
     *
     * <code>
     * $mailer->addReplyTo('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return static
     */
    public function addReplyTo($email, $name = '');
    
    /**
     * Add file into email as attachment
     *
     * @param string $path physical path to the file or content of file
     * @param string $name file name, set NULL to use default
     * @param string $mimeType mime type of file, set NULL to use default
     * @param int    $type Attachment's type (File or Content)
     * @return static
     * 
     * @throws InvalidParameterException
     */
    public function addAttachment($path, $name = null, $mimeType = null, $type = self::ATTACHMENT_FILE);
    
    /**
     * Add predefined header to email
     *
     * <code>
     * $mail->addHeader('Content-Type', 'text/plain'); // Content-Type: text/plain \r\n
     * </code>
     *
     * @param string $name header name
     * @param string $value header value
     * @return static
     * 
     * @throws InvalidParameterException
     */
    public function addHeader($name, $value);
    
    /**
     * Remove predefined header to email
     *
     * <code>
     * $mail->removeHeader('Content-Type');
     * </code>
     *
     * @param string $name header name
     * @return static
     * 
     * @throws InvalidParameterException
     */
    public function removeHeader($name);
    
    /**
     * Check if a header name has been registered
     *
     * <code>
     * $mail->hasHeader('Content-Type');
     * </code>
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader($name);
    
    /**
     * Clear all headers
     *
     * @return static
     */
    public function removeHeaders();
    
    /**
     * Set email character set
     *
     * <code>
     * $mail->setCharset('utf-8');
     * </code>
     *
     * @param string $charset
     * @return static
     */
    public function setCharset($charset);
    
    /**
     * Set email's subject
     *
     * @param string $subject
     * @return static
     * @throws Exception
     */
    public function setSubect($subject);
    
    /**
     * Set HTML email's body
     *
     * @param string $body
     * @return static
     */
    public function setBodyHtml($body);
    
    /**
     * Set Plain Text email's body
     *
     * @param string $body
     * @return static
     */
    public function setBodyPlainText($body);
    
    /**
     * Test email's configuration
     *
     * @return bool TRUE if the configuration is correct, FALSE if otherwise
     */
    public function test();
    
    /**
     * Send mail
     *
     * <code>
     * $mailer->addTo('email', 'name')
     *        ->addBcc('email', 'name')
     *        ->addCc('email', 'name')
     *        ->addFrom('email', 'name')
     *        ->setSubect('This is email\'s subject')
     *        ->setBodyPlainText('This is plain text body')
     *        ->setBodyHtml('<strong>This is HTML body</strong>')
     *        ->addAttachment('path_to_attachment')
     *        ->send();
     * </code>
     *
     * @return bool TRUE if success, FALSE if otherwise
     * @throws \Engine\Exception\Mail\InvalidAddressException
     * @throws \Engine\Exception\NullPointerException
     */
    public function send();
}