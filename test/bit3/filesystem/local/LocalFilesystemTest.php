<?php

namespace bit3\filesystem\local;

require_once(__DIR__ . '/../../../bootstrap.php');

use bit3\filesystem\iterator\FilesystemIterator;
use bit3\filesystem\iterator\RecursiveFilesystemIterator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-10-17 at 10:24:36.
 */
class LocalFilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocalFilesystem
     */
    protected $fs;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->fs = new LocalFilesystem(__DIR__ . '/../../../../src');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers bit3\filesystem\local\LocalFilesystem::getBasePath
     */
    public function testGetBasePath()
    {
        $real = realpath(__DIR__ . '/../../../../src') . '/';

        $this->assertEquals($real, $this->fs->getBasePath());
    }

    /**
     * @covers bit3\filesystem\local\LocalFilesystem::getRoot
     */
    public function testGetRoot()
    {
        $root = $this->fs->getRoot();

        $real = realpath(__DIR__ . '/../../../../src');

        $this->assertEquals($real, $root->getRealPath());
        $this->assertEquals('/', $root->__toString());
    }

    public function testTree()
    {
        $filesystemIterator = new RecursiveFilesystemIterator($this->fs->getRoot(), FilesystemIterator::CURRENT_AS_FILENAME);
        $treeIterator = new \RecursiveTreeIterator($filesystemIterator);

        foreach ($treeIterator as $path) {
            echo $path . "\n";
        }
    }
}