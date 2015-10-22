<?php namespace Engine\Io\File;

use Engine\Io\File\Parser\Contract as Parser;
use Engine\Io\File\Parser\Manager  as ParseManager;
use Engine\Exception\Io\FileNotFoundException;
use Engine\Exception\Io\AccessDeniedException;

class Factory implements Contract
{
    /**
     * File's name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Raw Content
     * 
     * @var string
     */
    protected $rawContent;
    
    /**
     * Parsed Content
     * 
     * @var mixed 
     */
    protected $content;
    
    /**
     * File's size
     * 
     * @var int
     */
    protected $size;
    
    /**
     * File extension
     * 
     * @var string 
     */
    protected $ext;
    
    /**
     * File's mimeType
     * 
     * @var string
     */
    protected $mimeType;
    
    /**
     * File's path
     * 
     * @var string
     */
    protected $path;
    
    /**
     * File is readble or not
     * 
     * @var bool
     */
    protected $isReadable = false;
    
    /**
     * File is writable or not
     * 
     * @var bool 
     */
    protected $isWritable = false;

    public function chmod($mode)
    {
        return chmod($this->path, $mode);
    }

    public function chown($user)
    {
        return chown($this->path, $user);
    }

    public function copy($destination)
    {
        return copy($this->path, $destination);
    }

    public function delete()
    {
        if ($this->isWritable) {
            return unlink($this->path);
        } else {
            return false;
        }
    }

    public function getContent(Parser $parser = null)
    {
        if (is_null($parser)) {
            if (is_null($this->content)) {
                $parser = ParseManager::getParser($this);
            } else {
                return $this->content;
            }
        }
        
        if (is_null($this->rawContent)) {
            $this->rawContent = file_get_contents($this->path);
        }
        
        $this->content = $parser->parse($this->rawContent);
        return $this->content;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function isReadable()
    {
        return $this->isReadable;
    }

    public function isWritable()
    {
        return $this->isWritable;
    }

    public function move($destination)
    {
        return $this->copy($destination) & $this->delete();
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function process($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("$path could not be found");
        }
        
        if (!is_readable($path)) {
            throw new AccessDeniedException("$path is not readable");
        }
        $this->isReadable = true;
        
        if (is_writable($path)) {
            $this->isWritable = true;
        }
        
        $this->setPath($path);
        if (!$this->processFileNameAndExtension()) {
            return 1;
        }
        $this->size = filesize($path);
        if (!$this->processFileMimeType()) {
            return 2;
        }
        
        return true;
    }
    
    protected function processFileNameAndExtension()
    {
        $name    = basename($this->path);
        $pattern = '/(.*)\.([a-z0-9]+)$/i';
        if (preg_match($pattern, $name, $matches)) {
            $this->name = $matches[1];
            $this->ext  = $matches[2];
            return true;
        }
        
        return false;
    }

    protected function processFileMimeType()
    {
        if (function_exists('finfo_open')) { // use PECL file_info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->mimeType = finfo_file($finfo, $this->path);
            finfo_close($finfo);
            return true;
        } else { // use common MIME_TYPE list
            $this->mimeType = MimeType::get($this->ext);
            return empty($this->mimeType) ? false : true;
        }
        
        return false;
    }

    public function getMode($toString = false)
    {
        if ($toString) {
            return $this->getModeString();
        }
        
        return substr(sprintf('%o', fileperms($this->path)), -4);
    }

    protected function getModeString()
    {
        $perms = fileperms($this->path);
        
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                        (($perms & 0x0800) ? 's' : 'x' ) :
                        (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                        (($perms & 0x0400) ? 's' : 'x' ) :
                        (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                        (($perms & 0x0200) ? 't' : 'x' ) :
                        (($perms & 0x0200) ? 'T' : '-'));
        
        return $info;
    }
}