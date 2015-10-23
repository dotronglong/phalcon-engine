<?php namespace Engine\IO\File;

use Engine\IO\File\Parser\Contract as Parser;
use Engine\Exception\IO\FileNotFoundException;

interface Contract
{    
    /**
     * Get file name
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Get file size
     * 
     * @return int
     */
    public function getSize();
    
    /**
     * Get file extension
     * 
     * @return string
     */
    public function getExt();
    
    /**
     * Get file mime_type
     * 
     * @return string
     */
    public function getMimeType();
    
    /**
     * Get file content. If the parser is not specified, it will automatically
     * create based on the file's mime type and extension. In case there is no
     * parser available, it will return raw content
     * 
     * @param Parser $parser
     * @return mixed
     */
    public function getContent(Parser $parser = null);
    
    /**
     * Get file's raw content
     * 
     * @return string
     */
    public function getRawContent();
    
    /**
     * Get file path
     * 
     * @return string
     */
    public function getPath();
    
    /**
     * Set file path
     * 
     * @param string $path absolute path to the file
     * @return static
     */
    public function setPath($path);
    
    /**
     * Process file based on path
     * 
     * @param string $path
     * @return bool TRUE on success, FALSE on error
     * 
     * @throws FileNotFoundException
     */
    public function process($path);
    
    /**
     * File is readable or not
     * 
     * @return bool
     */
    public function isReadable();
    
    /**
     * File is writable or not
     * 
     * @return bool
     */
    public function isWritable();
    
    /**
     * Move file to destination path
     * 
     * @param string $destination
     * @return bool TRUE on success, FALSE on error
     */
    public function move($destination);
    
    /**
     * Copy file to destination path
     * 
     * @param string $destination
     * @return bool TRUE on success, FALSE on error
     */
    public function copy($destination);
    
    /**
     * Delete file
     * 
     * @return bool TRUE on success, FALSE on error
     */
    public function delete();
    
    /**
     * Change file's mode
     * 
     * @param int $mode
     * @return bool TRUE on success, FALSE on error
     */
    public function chmod($mode);
    
    /**
     * Change file's owner
     * 
     * @param string|int $user
     * @return bool TRUE on success, FALSE on error
     */
    public function chown($user);
    
    /**
     * Get file mode (permissions)
     * @param bool $toString
     * @return string
     */
    public function getMode($toString = false);
}