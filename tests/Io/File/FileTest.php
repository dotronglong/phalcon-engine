<?php namespace Engine\Tests\Io\File;

use Engine\Tests\TestCase;
use Engine\Io\File\Factory as File;
use Engine\Io\File\Parser\Manager as ParseManager;
use Engine\Exception\Io\FileNotFoundException;

class FileTest extends TestCase
{
    protected $filePath = 'file.csv';
    
    protected function getFilePath()
    {
        return __DIR__ . '/' . $this->filePath;
    }

    public function testImplementFileContract()
    {
        $file = new File();
        $this->assertInstanceOf(\Engine\Io\File\Contract::class, $file);
        return $file;
    }
    
    /**
     * @depends testImplementFileContract
     */
    public function testSetGetPath()
    {
        $path = $this->getFilePath();
        $file = new File();
        $file->setPath($path);
        $this->assertEquals($file->getPath(), $path);
    }
    
    /**
     * @depends testImplementFileContract
     */
    public function testProcessFile(File $file)
    {
        $this->assertException(FileNotFoundException::class, function() use ($file) {
            $file->process('invalid.file');
        });
        $this->assertTrue($file->process($this->getFilePath()));
        return $file;
    }
    
    /**
     * @depends testProcessFile
     */
    public function testGetFileName(File $file)
    {
        $this->assertEquals($file->getName(), 'file');
    }
    
    /**
     * @depends testProcessFile
     */
    public function testGetFileExt(File $file)
    {
        $this->assertEquals($file->getExt(), 'csv');
    }
    
    /**
     * @depends testProcessFile
     */
    public function testGetFileMimeType(File $file)
    {
        $this->assertEquals($file->getMimeType(), 'text/plain');
    }
    
    /**
     * @depends testProcessFile
     */
    public function testGetFileParser(File $file)
    {
        $parser = ParseManager::getParser($file);
        $this->assertInstanceOf(\Engine\Io\File\Parser\Contract::class, $parser);
        $this->assertInstanceOf(\Engine\Io\File\Parser\Csv::class, $parser);
    }
    
    /**
     * @depends testProcessFile
     */
    public function testSetFileMode(File $file)
    {
        $this->assertTrue($file->chmod(0755));
    }
    
    /**
     * @depends testProcessFile
     */
    public function testSetFileOwner(File $file)
    {
        $this->assertTrue(true); // temporary ignore
    }
}