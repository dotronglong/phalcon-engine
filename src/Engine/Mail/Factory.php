<?php namespace Engine\Mail;

use Engine\Exception\NullPointerException;
use Engine\Exception\InvalidParameterException;

defined('RN') || define('RN', "\r\n");

abstract class Factory implements Contract
{
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
    protected $errors = [];

    public function __construct($config = [])
    {
        $this->config = $config;

        if (isset($config['charset'])) {
            $this->setCharset($config['charset']);
        }
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : (in_array($name, [
            self::MAIL_TO,
            self::MAIL_BCC,
            self::MAIL_CC
        ]) ? [] : null);
    }
    
    public function removeErrors()
    {
        $this->errors = [];

        return $this;
    }
    
    public function getErrors($toString = false)
    {
        if (!$toString) {
            return $this->errors;
        }
        
        $errors = '';
        if (count($this->errors)) {
            foreach ($this->errors as $error) {
                $errors .= $error['message'] . (empty($error['errstr']) ? ' :' . $error['errstr'] : '') . (empty($error['errno']) ? ' [ERR: ' . $error['errno'] . ']' : '') . RN;
            }
        }
        return $errors;
    }
    
    public function addError($message, $err_no = null, $err_str = null)
    {
        $this->errors[] = [
            'message' => $message,
            'errno'   => $err_no,
            'errstr'  => $err_str
        ];
        
        return $this;
    }

    public function getConfig($name = null, $default = null)
    {
        if ($name === null) {
            return $this->config;
        }

        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }
    
    public function setConfig($config = null, $value = null)
    {
        if (is_array($config)) {
            $this->config = $config;
        } else {
            $this->config[$config] = $value;
        }
    }

    public function setConfigArray($config, $merge = true)
    {
        if ($merge) {
            $this->config = array_merge($this->config, $config);
        } else {
            $this->config = $config;
        }
    }
    
    protected function addAddress($type, $email, $name = '', $merge = true)
    {
        if (empty($email)) {
            throw new NullPointerException('Email must not be empty');
        }
        $address = [
            'email' => $email,
            'name'  => $name
        ];
        if ($merge) {
            $this->data[$type][] = $address;
        } else {
            $this->data[$type]   = $address;
        }

        return $this;
    }
    
    public function addTo($email, $name = '')
    {
        return $this->addAddress(self::MAIL_TO, $email, $name);
    }
    
    public function addCc($email, $name = '')
    {
        return $this->addAddress(self::MAIL_CC, $email, $name);
    }
    
    public function addBcc($email, $name = '')
    {
        return $this->addAddress(self::MAIL_BCC, $email, $name);
    }
    
    public function addFrom($email, $name = '')
    {
        return $this->addAddress(self::MAIL_FROM, $email, $name, false);
    }
    
    public function addReplyTo($email, $name = '')
    {
        $this->data[self::MAIL_REPLY_TO] = null;

        return $this->addAddress(self::MAIL_REPLY_TO, $email, $name);
    }
    
    protected function addAttachmentContent($content, $name, $mimeType)
    {
        $content = 'Content-Type: ' . $mimeType . '; name="' . $name . '"' . RN .
            'Content-Transfer-Encoding: base64' . RN .
            'Content-ID: <' . $name . '>' . RN . RN .
            chunk_split(base64_encode($content)) . RN . RN;

        $this->data[self::MAIL_ATTACHMENT][] = $content;

        return $this;
    }
    
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
                throw new InvalidParameterException('Name and MimeType must be set if content is not a file');
            }
        }

        return $this;
    }
    
    public function addHeader($name, $value)
    {
        if (empty($name)) {
            throw new InvalidParameterException('Header\'s name must not be empty');
        }
        $this->data[self::MAIL_HEADER][$name] = $value;

        return $this;
    }
    
    public function removeHeader($name)
    {
        if (isset($this->data[self::MAIL_HEADER][$name])) {
            unset($this->data[self::MAIL_HEADER][$name]);
        }
        
        return $this;
    }
    
    public function removeHeaders()
    {
        $this->data[self::MAIL_HEADER] = [];
    }
    
    public function hasHeader($name)
    {
        return isset($this->data[self::MAIL_HEADER][$name]);
    }
    
    public function clearHeader()
    {
        $this->data[self::MAIL_HEADER] = null;

        return $this;
    }
    
    public function setCharset($charset)
    {
        $this->data[self::MAIL_CHARSET] = $charset;

        return $this;
    }
    
    public function setSubect($subject)
    {
        if (empty($subject)) {
            throw new InvalidParameterException('Subject must not be empty');
        }
        $this->data[self::MAIL_SUBJECT] = $subject;

        return $this;
    }
    
    protected function setBodyContent($body, $type = self::MAIL_BODY_PLAIN)
    {
        if (empty($body)) {
            throw new InvalidParameterException('Body must not be empty');
        }
        $this->data[$type] = $body;

        return $this;
    }
    
    protected function setBody($body)
    {
        return $this->setBodyContent($body);
    }
    
    public function setBodyHtml($body)
    {
        return $this->setBodyContent($body, self::MAIL_BODY_HTML);
    }
    
    public function setBodyPlainText($body)
    {
        return $this->setBodyContent(strip_tags($body), self::MAIL_BODY_PLAIN);
    }

    /**
     * Get built body
     *
     * @return string
     */
    protected function build()
    {
        $bodyPlain = $this->body_plain;
        $bodyHtml  = str_replace('=', '=3D', $this->body_html);

        $charset     = $this->charset ? 'charset="' . $this->charset . '"' : '';
        $attachments = $this->attachment;

        $boundary         = 'MAIL_' . md5(rand()) . '_Msg';
        $boundary_content = 'MAIL_' . md5(rand()) . '_Body';
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
    
    public function toString($type, $args = [])
    {
        $return = '';
        if (!isset($this->data[$type])) {
            return $return;
        }
        
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
    
    public function test()
    {
        return true;
    }
}
