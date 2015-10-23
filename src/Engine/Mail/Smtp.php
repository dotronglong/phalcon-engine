<?php namespace Engine\Mail;

use Engine\Exception\Io\ConnectionFailedException;
use Engine\Mail\Factory as Mail;

class Smtp extends Mail
{
    const SMTP_DEFAULT_PORT    = 25;
    const SMTP_DEFAULT_TIMEOUT = 60;
    const SMTP_DEFAULT_ENCRYPT = Mail::ENCRYPT_SSL;

    const AUTH_NONE  = false;
    const AUTH_PLAIN = 'PLAIN';
    const AUTH_LOGIN = 'LOGIN';
    const AUTH_NTLM  = 'NTLM';
    const AUTH_MD5   = 'CRAM-MD5';

    const RESP_SERVICE_READY    = 220;
    const RESP_SERVICE_CLOSING  = 221;
    const RESP_AUTH_OK          = 235;
    const RESP_OK               = 250;
    const RESP_USER_OK          = 251;
    const RESP_SERVER_CHALLENGE = 334;
    const RESP_START_INPUT      = 354;

    const BUFFER_LENGTH = 1024;
    const MAIL_CONTENT  = 'content';

    /**
     * Only retrieve the first line of response
     * @var bool
     */
    public static $getFirstLineResponseOnly = true;

    /**
     * Allow track to debug
     * @var boolean
     */
    private $allowDebug = false;

    /**
     * Debug's Messages
     * @var array
     */
    private $debugMessages = [];

    /**
     * SMTP Connection
     * @var mixed
     */
    protected $connection;

    /**
     * Connection Information
     * @var array
     */
    protected $connectionInfo;

    /**
     * Last response received from SMTP Server
     * @var string
     */
    protected $lastResponse;

    /**
     * Last Command sent to SMTP Server
     * @var string
     */
    protected $lastCommand;

    /**
     * List of email is sent to
     * @var array
     */
    protected $emailSent = [];

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->allowDebug = $this->getConfig('debug', $this->allowDebug);
    }

    /**
     * Add SMTP Mail data
     *
     * @param string  $content
     * @param boolean $merge
     * @return \Engine\Mail\Smtp
     */
    protected function addContent($content, $merge = true)
    {
        if ($merge) {
            $this->data[self::MAIL_CONTENT][] = $content;
        } else {
            $this->data[self::MAIL_CONTENT] = $content;
        }

        return $this;
    }

    /**
     * Add debug's message
     * @param string $message
     * @return \Engine\Mail\Smtp
     */
    protected function addDebugMessage($message)
    {
        if ($this->allowDebug) {
            $this->debugMessages[] = $message;
        }

        return $this;
    }

    /**
     * Get Debug's Messages
     * @return string|array
     */
    protected function getDebugMessages($toString = true)
    {
        if (!$toString) {
            return $this->debugMessages;
        }
        $debugMessages = '';
        if (is_array($this->debugMessages) && count($this->debugMessages)) {
            foreach ($this->debugMessages as $message) {
                $debugMessages .= $message . RN;
            }
        }

        return $debugMessages;
    }

    public function toString($type, $args = [])
    {
        if ($type === self::MAIL_CONTENT) {
            $result = '';
            if (is_array($this->data[self::MAIL_CONTENT]) && count($this->data[self::MAIL_CONTENT])) {
                foreach ($this->data[self::MAIL_CONTENT] as $content) {
                    $result .= $content . RN;
                }
            }

            return $result;
        } else {
            return parent::toString($type, $args);
        }
    }

    /**
     * Get connection's information (meta data)
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    protected function getConnectionInfo($name = null, $default = null)
    {
        return $name === null ? $this->connectionInfo : (isset($this->connectionInfo[$name]) ? $this->connectionInfo[$name] : $default);
    }

    /**
     * Is connected to SMTP server or not
     * @return type
     */
    protected function isConnected($throw = false)
    {
        if ($throw && !$this->connection) {
            throw new ConnectionFailedException('There is no connection to SMTP Server');
        } else if ($this->allowDebug && !$this->connection) {
            return $this->addError('There is no connection to SMTP Server');
        }

        return $this->connection ? true : false;
    }

    protected function connect()
    {
        if ($this->isConnected()) {
            return $this->addError('Already connected to SMTP server');
        }
        $encrypt = $this->getConfig('encrypt', self::SMTP_DEFAULT_ENCRYPT);
        $host    = $this->getConfig('host', 'localhost');
        $port    = (int) $this->getConfig('port', self::SMTP_DEFAULT_PORT);
        $timeout = (int) $this->getConfig('timeout', self::SMTP_DEFAULT_TIMEOUT);
        $options = $this->getConfig('options', []);

        /**
         * Create stream context if neccessary
         */
        $errno   = E_USER_ERROR;
        $errstr  = '';
        $context = stream_context_create($options);

        /**
         * Open connection to STMP server
         */
        $this->connection = fsockopen($host, $port, $errno, $errstr, $timeout);

        if ($this->connection === false) {
            return $this->addError('Unable to connect to the SMTP server');
        }

        $this->addDebugMessage('[ + ] Connected to SMTP Server');
        $this->addDebugMessage('[RES] ' . $this->getResponse());

        if (!$this->hello($host)) {
            return false;
        }

        if ($encrypt === Mail::ENCRYPT_TLS) {
            if (!$this->exec('STARTTLS', self::RESP_SERVICE_READY)) {
                return false;
            }

            if (!stream_socket_enable_crypto($this->connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                return false;
            }
            if (!$this->hello($host)) {
                return false;
            }
        }

        $this->addDebugMessage('[ + ] Server is ready for authentication');

        return true;
    }

    /**
     * Authenticate to SMTP Server
     *
     * @param string $authType
     * @param string $realm The auth realm for NTLM, ignore in this version
     * @return boolean true if succes, false if otherwise
     */
    protected function authenticate($authType = self::AUTH_LOGIN, $realm = '')
    {
        $user     = $this->getConfig('user');
        $pwd      = $this->getConfig('pwd');
        $authType = $this->getConfig('auth', $authType);

        switch ($authType) {
            case self::AUTH_LOGIN:
                if (!$this->exec('AUTH LOGIN', [
                    self::RESP_OK,
                    self::RESP_SERVER_CHALLENGE
                ])
                ) {
                    $this->addDebugMessage('Failed to use AUTH LOGIN. Server denied.');

                    return false;
                }
                if (!$this->exec(base64_encode($user), [
                    self::RESP_OK,
                    self::RESP_SERVER_CHALLENGE
                ])
                ) {
                    $this->addDebugMessage("$user is rejected by server");

                    return false;
                }
                if (!$this->exec(base64_encode($pwd), [
                    self::RESP_OK,
                    self::RESP_AUTH_OK
                ])
                ) {
                    $this->addDebugMessage('Invalid password for email ' . $user);

                    return false;
                }
                break;

            case self::AUTH_PLAIN:
                if (!$this->exec('AUTH PLAIN', self::RESP_SERVER_CHALLENGE)) {
                    $this->addDebugMessage('Failed to use AUTH PLAIN. Server denied.');

                    return false;
                }
                if (!$this->exec(base64_encode("\0" . $user . "\0" . $pwd), self::RESP_AUTH_OK)) {
                    $this->addDebugMessage("Invalid user or password!");

                    return false;
                }
                break;

            case self::AUTH_MD5:
                if (!$this->exec('AUTH CRAM-MD5', self::RESP_SERVER_CHALLENGE)) {
                    $this->addDebugMessage('Failed to use AUTH MD5. Server denied.');

                    return false;
                }

                $challenge = base64_decode(substr($this->lastResponse, 4));

                $this->exec($user . ' ' . Mail::hmac($challenge, $pwd), self::RESP_AUTH_OK);
                break;

            case self::AUTH_NTLM:
                return false;
                break;

            case self::AUTH_NONE:
            default:
                return true;
                break;
        }

        $this->addDebugMessage('[ + ] Authentication successfully.');

        return true;
    }

    /**
     * Get response from connection
     * @return string
     */
    protected function getResponse()
    {
        if (!$this->isConnected()) {
            return false;
        }

        $timeout  = (int) $this->getConfig('timeout', self::SMTP_DEFAULT_TIMEOUT);
        $forceEnd = time() + $timeout;
        $response = '';

        do {
            $buffer = fgets($this->connection, self::BUFFER_LENGTH);
            $response .= $buffer;
            if (self::$getFirstLineResponseOnly || $buffer[3] !== '-') {
                break;
            }
        } while ($buffer !== false && !feof($this->connection) && $forceEnd > time());

        $this->lastResponse = $response;

        return $response;
    }

    /**
     * Send EHLO|HELO to SMTP server
     *
     * @param string $host
     * @return boolean
     */
    protected function hello($host)
    {
        if (!$this->exec('EHLO' . " $host")) {
            if (!$this->exec('HELO ' . " $host")) {
                return false;
            }
        }

        return true;
    }

    /**
     * Quit connection
     *
     * @return boolean
     */
    protected function quit()
    {
        if (!$this->connection) {
            return false;
        }

        $this->exec('QUIT', self::RESP_SERVICE_CLOSING);
        fclose($this->connection);
        $this->connection = null;

        if ($this->allowDebug) {
            echo $this->getDebugMessages();
        }

        return true;
    }

    /**
     * Execute a command
     *
     * <code>
     * // if response's code equal self::ERROR_OK it will return true, false if otherwise
     * $this->exec('EHLO', self::ERROR_OK);
     * </code>
     *
     * @param string    $command
     * @param int|array $validCode
     * @param boolean   $quit Quit on ERROR or not
     * @return boolean
     */
    protected function exec($command, $validCode = self::RESP_OK, $quit = false)
    {
        if (!$this->isConnected(true)) {
            return false;
        }

        $this->addDebugMessage('[CMD] ' . $command);
        $this->lastCommand = $command;
        if (fwrite($this->connection, $command . RN) === false) {
            return false;
        }

        if ($validCode) {
            $response = $this->getResponse();
            $code     = substr($response, 0, 3);
            $result   = null;
            $this->addDebugMessage('[RES] ' . join(RN . "      ", explode(RN, $response)));
            if (is_array($validCode)) {
                $result = in_array($code, $validCode);
            } else if ($code == $validCode) {
                $result = true;
            } else {
                $result = false;
            }

            if ($quit && !$result) {
                $this->quit();
            }

            return $result;
        }

        return true;
    }

    /**
     * Test mail
     *
     * @see Mail::test
     * @return boolean
     */
    public function test()
    {
        if (!$this->isConnected()) {
            if (!$this->connect()) {
                $this->quit();

                return false;
            }
        }

        $authType = $this->getConfig('auth', self::AUTH_LOGIN);
        if (!$this->authenticate($authType)) {
            $this->quit();

            return false;
        }

        $this->quit();

        return true;
    }

    public function send()
    {
        $this->addHeader('X-Mailer', 'Phalcon Engine Mailer');
        
        if (!$this->isConnected()) {
            if (!$this->connect()) {
                return $this->quit();
            }
        }

        $authType = $this->getConfig('auth', self::AUTH_LOGIN);
        if (!$this->authenticate($authType)) {
            return $this->quit();
        }

        $from = $this->from;
        if ($from && is_array($from)) {
            $this->exec("MAIL FROM:<{$from['email']}>", self::RESP_OK);
        }

        $to = array_merge($this->to, $this->bcc, $this->cc);
        if (!is_array($to) || !count($to)) {
            return false;
        }
        foreach ($to as $rcpt) {
            if ($this->exec("RCPT TO:<{$rcpt['email']}>", [
                self::RESP_OK,
                self::RESP_USER_OK
            ])
            ) {
                $this->emailSent[] = $rcpt['email'];
            }
        }

        /**
         * Request to start DATA
         */
        $this->exec('DATA', [
            self::RESP_OK,
            self::RESP_START_INPUT
        ], true);

        $replyTo = $this->toString(self::MAIL_REPLY_TO);
        $cc      = $this->toString(self::MAIL_CC);

        if (!empty($replyTo)) {
            $this->addHeader('Reply-To', $replyTo);
        }
        if (!empty($cc)) {
            $this->addHeader('Cc', $cc);
        }
        $this->addHeader('Subject', $this->subject);

        $body = $this->build();
        $data = $this->addContent('Date: ' . date('d M y H:i:s'))
            ->addContent('From: ' . $this->toString(self::MAIL_FROM))
            ->addContent('To: ' . $this->toString(self::MAIL_TO))
            ->addContent($this->toString(self::MAIL_HEADER))
            ->addContent($body)
            ->addContent('.')
            ->toString(self::MAIL_CONTENT);

        if (!empty($data)) {
            $this->exec($data, self::RESP_OK);
        }

        $this->quit();

        return true;
    }
}
