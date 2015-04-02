<?php namespace Engine\Mail;

use Engine\Mail\Exception;

defined('RN') || define('RN', "\r\n");

abstract class Mail
{
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
     * Mail Configuration
     * @var array
     */
    protected $config;

    /**
     * Mail Data & Settings
     * @var array
     */
    protected $data;

    /**
     * Errors
     * @var array
     */
    protected $errors = array();

    /**
     * Default Constructor
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;

        if (isset($config['charset'])) {
            $this->setCharset($config['charset']);
        }
        $this->addHeader('X-Mailer', 'GMS Framework Mailer');
    }

    /**
     * Add an error to queue
     * @param string $message error's message
     * @param string $errstr error's content
     * @param int    $errno error's number identity
     * @return boolean
     */
    protected function addError($message, $errstr = null, $errno = null)
    {
        $this->errors[] = array(
            'message' => $message,
            'errstr'  => $errstr,
            'errno'   => $errno
        );

        return false;
    }

    /**
     * Clear errors
     * @return \Engine\Mail\Mail
     */
    protected function clearErrors()
    {
        $this->errors = array();

        return $this;
    }

    /**
     * Get errors
     * @param boolean $toString return string instead of array
     * @return string|array
     */
    public function getErrors($toString = false)
    {
        if ($toString) {
            if (count($this->errors)) {
                $errors = '';
                foreach ($this->errors as $error) {
                    $errors .= $error['message'] . (empty($error['errstr']) ? ' :' . $error['errstr'] : '') . (empty($error['errno']) ? ' [ERR: ' . $error['errno'] . ']' : '') . RN;
                }

                return $errors;
            }
        }

        return $this->errors;
    }

    /**
     * Get / Set Configuration
     *
     * <code>
     * $mail->setConfig(array(
     *     'host' => 'host_name',
     *     'user' => 'user_name',
     *     [...]
     * )); // set configuration
     *
     * $mail->setConfig('host', 'host_name'); // set configuration by name
     * </code>
     *
     * @param mixed $config
     * @param mixed $value
     */
    public function setConfig($config = null, $value = null)
    {
        if (is_array($config)) {
            $this->config = $config;
        } else {
            $this->config[$config] = $value;
        }
    }

    /**
     * Get configuration
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getConfig($name = null, $default = null)
    {
        if ($name === null) {
            return $this->config;
        }

        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }

    /**
     * Add an address
     * @param string  $type address's type
     * @param string  $email email address
     * @param string  $name name of owner's email
     * @param boolean $merge Merge with current addresses
     * @return \Engine\Mail\Mail
     * @throws Exception
     */
    private function addAddress($type, $email, $name = '', $merge = true)
    {
        if (empty($email)) {
            throw new Exception('Email must not be empty');
        }
        $address = array(
            'email' => $email,
            'name'  => $name
        );
        if ($merge) {
            $this->data[$type][] = $address;
        } else {
            $this->data[$type] = $address;
        }

        return $this;
    }

    /**
     * Add an email address to TO section
     *
     * <code>
     * $mailer->addTo('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return \Engine\Mail\Mail
     */
    public function addTo($email, $name = '')
    {
        return $this->addAddress(self::MAIL_TO, $email, $name);
    }

    /**
     * Add an email address to CC section
     *
     * <code>
     * $mailer->addCc('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return \Engine\Mail\Mail
     */
    public function addCc($email, $name = '')
    {
        return $this->addAddress(self::MAIL_CC, $email, $name);
    }

    /**
     * Add an email address to BCC section
     *
     * <code>
     * $mailer->addBcc('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return \Engine\Mail\Mail
     */
    public function addBcc($email, $name = '')
    {
        return $this->addAddress(self::MAIL_BCC, $email, $name);
    }

    /**
     * Add an email address to FROM section
     *
     * <code>
     * $mailer->addFrom('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return \Engine\Mail\Mail
     */
    public function addFrom($email, $name = '')
    {
        return $this->addAddress(self::MAIL_FROM, $email, $name, false);
    }

    /**
     * Add an email address to REPLY-TO section
     *
     * <code>
     * $mailer->addReplyTo('abc@domain.com', 'ABC');
     * </code>
     *
     * @param string $email
     * @param string $name
     * @return \Engine\Mail\Mail
     */
    public function addReplyTo($email, $name = '')
    {
        $this->data[self::MAIL_REPLY_TO] = null;

        return $this->addAddress(self::MAIL_REPLY_TO, $email, $name);
    }

    /**
     * Add
     * @param string $content
     * @return \Engine\Mail\Mail
     */
    protected function addAttachmentContent($content, $name, $mimeType)
    {
        $content = 'Content-Type: ' . $mimeType . '; name="' . $name . '"' . RN .
            'Content-Transfer-Encoding: base64' . RN .
            'Content-ID: <' . $name . '>' . RN . RN .
            chunk_split(base64_encode($content)) . RN . RN;

        $this->data[self::MAIL_ATTACHMENT][] = $content;

        return $this;
    }

    /**
     * Add file into email as attachment
     *
     * @param string $path physical path to the file or content of file
     * @param string $name file name, set NULL to use default
     * @param string $mimeType mime type of file, set NULL to use default
     * @param int    $type Attachment's type (File or Content)
     * @return \Engine\Mail\Mail
     */
    public function addAttachment($path, $name = null, $mimeType = null, $type = self::ATTACHMENT_FILE)
    {
        if ($type === self::ATTACHMENT_FILE) {
            $info = GMS_File::_($path)->fileInfo();
            if (is_array($info) && count($info)) {
                $name     = $name ? $name : $info['name'];
                $mimeType = $mimeType ? $mimeType : $info['mime_type'];

                return $this->addAttachmentContent(file_get_contents($path), $name, $mimeType);
            }
        } else if ($type === self::ATTACHMENT_CONTENT) {
            if (!empty($name) && !empty($mimeType)) {
                return $this->addAttachmentContent($path, $name, $mimeType);
            } else {
                throw new Exception('Name and MimeType must be set if content is not a file');
            }
        }

        return $this;
    }

    /**
     * Add predefined header to email
     *
     * <code>
     * $mail->addHeader('Content-Type', 'text/plain'); // Content-Type: text/plain \r\n
     * </code>
     *
     * @param string $name header name
     * @param string $value header value
     * @return \Engine\Mail\Mail
     * @throws Exception
     */
    public function addHeader($name, $value)
    {
        if (empty($name)) {
            throw new Exception('Header\'s name must not be empty');
        }
        $this->data[self::MAIL_HEADER][$name] = $value;

        return $this;
    }

    /**
     * Check if a header name has been registered
     *
     * <code>
     * $mail->hasHeader('Content-Type');
     * </code>
     *
     * @param string $name
     * @return boolean
     */
    public function hasHeader($name)
    {
        return isset($this->data[self::MAIL_HEADER][$name]);
    }

    /**
     * Clear all headers
     *
     * @return \Engine\Mail\Mail
     */
    public function clearHeader()
    {
        $this->data[self::MAIL_HEADER] = null;

        return $this;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : (in_array($name, array(
            self::MAIL_TO,
            self::MAIL_BCC,
            self::MAIL_CC
        )) ? array() : null);
    }

    /**
     * Set email character set
     *
     * <code>
     * $mail->setCharset('utf-8');
     * </code>
     *
     * @param string $charset
     * @return \Engine\Mail\Mail
     */
    public function setCharset($charset)
    {
        $this->data[self::MAIL_CHARSET] = $charset;

        return $this;
    }

    /**
     * Set email's subject
     *
     * @param string $subject
     * @return \Engine\Mail\Mail
     * @throws Exception
     */
    public function setSubect($subject)
    {
        if (empty($subject)) {
            throw new Exception('Subject must not be empty');
        }
        $this->data[self::MAIL_SUBJECT] = $subject;

        return $this;
    }

    /**
     * Set email body content
     * @param string $body
     * @param string $type
     * @return \Engine\Mail\Mail
     * @throws Exception
     */
    protected function setBodyContent($body, $type = self::MAIL_BODY_PLAIN)
    {
        if (empty($body)) {
            throw new Exception('Body must not be empty');
        }
        $this->data[$type] = $body;

        return $this;
    }

    /**
     * An alias of setBodyContent
     *
     * @see GMS_Mail_Abstract::setBodyContent
     * @param string $body
     */
    public function setBody($body)
    {
        return $this->setBodyContent($body);
    }

    /**
     * Set HTML email's body
     *
     * @param string $body
     * @return \Engine\Mail\Mail
     */
    public function setBodyHtml($body)
    {
        return $this->setBodyContent($body, self::MAIL_BODY_HTML);
    }

    /**
     * Set Plain Text email's body
     *
     * @param string $body
     * @return \Engine\Mail\Mail
     */
    public function setBodyPlainText($body)
    {
        return $this->setBodyContent(strip_tags($body), self::MAIL_BODY_PLAIN);
    }

    /**
     * Get built body
     *
     * @return string
     */
    public function buildBody()
    {
        $bodyPlain = $this->body_plain;
        $bodyHtml  = str_replace('=', '=3D', $this->body_html);

        $charset     = $this->charset ? 'charset="' . $this->charset . '"' : '';
        $attachments = $this->attachment;

        $boundary         = 'GMS_' . md5(rand()) . '_Msg';
        $boundary_content = 'GMS_' . md5(rand()) . '_Body';
        $transferEncoding = 'quoted-printable';

        $this->addHeader('Mime-Version', '1.0');
        $this->addHeader('Content-Type', 'multipart/related; boundary="' . $boundary . '"');

        $body = "--$boundary" . RN;
        $body .= 'Content-Type: multipart/alternative;' . " boundary=\"$boundary_content\"" . RN . RN;
        if (!empty ($bodyPlain)) {
            $body .= "--$boundary_content" . RN;
            $body .= "Content-Type: text/plain; $charset" . RN . RN;
            $body .= $bodyPlain . RN . RN;
        }
        if (!empty ($bodyHtml)) {
            $body .= "--$boundary_content" . RN;
            $body .= "Content-Type: text/html; $charset" . RN;
            $body .= "Content-Transfer-Encoding: $transferEncoding" . RN . RN;
            $body .= $bodyHtml . RN . RN;
        }
        $body .= "--$boundary_content--" . RN;

        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                $body .= RN . "--$boundary" . RN;
                $body .= $attachment;
            }
        }
        $body .= RN . "--$boundary--";

        return $body;
    }

    /**
     * Get string values of Mail's data
     * @param string $type
     * @param array  $args
     * @return string
     */
    public function toString($type, $args = array())
    {
        $return = '';
        switch ($type) {
            case self::MAIL_TO:
            case self::MAIL_CC:
            case self::MAIL_BCC:
                if (isset($this->data[$type]) && is_array($this->data[$type])) {
                    $flag = false;
                    foreach ($this->data[$type] as $address) {
                        $name  = $address['name'];
                        $email = $address['email'];
                        if (!$flag) {
                            $flag = true;
                        } else {
                            $return .= ', ';
                        }
                        $return .= (empty($name) ? '' : '"' . $name . '" ') . "<$email>";
                    }
                }
                break;

            case self::MAIL_FROM:
                $name   = $this->data[$type]['name'];
                $email  = $this->data[$type]['email'];
                $return = (empty($name) ? '' : '"' . $name . '" ') . "<$email>";
                break;

            case self::MAIL_HEADER:
                if (isset($this->data[self::MAIL_HEADER])
                    && is_array($this->data[self::MAIL_HEADER]) && count($this->data[self::MAIL_HEADER])
                ) {
                    $CRLF = isset($args['LF']) ? false : true;
                    foreach ($this->data[self::MAIL_HEADER] as $name => $value) {
                        $return .= "$name: $value" . ($CRLF ? RN : "\n");
                    }
                }
                break;

            default:
                break;
        }

        return $return;
    }

    /**
     * Test email's configuration
     *
     * @return boolean TRUE if the configuration is correct, FALSE if otherwise
     */
    public function test()
    {
        return true;
    }

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
     * @return boolean TRUE if success, FALSE if otherwise
     */
    abstract public function send();
}