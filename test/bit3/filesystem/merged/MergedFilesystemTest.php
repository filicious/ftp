<?php

/**
 * High level object oriented filesystem abstraction.
 *
 * @package php-filesystem
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @link    http://bit3.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace bit3\filesystem\merged;

require_once(__DIR__ . '/../../../bootstrap.php');

use bit3\filesystem\local\LocalFilesystem;
use bit3\filesystem\iterator\FilesystemIterator;
use bit3\filesystem\iterator\RecursiveFilesystemIterator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-10-17 at 10:47:54.
 */
class MergedFilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MergedFilesystem
     */
    protected $merged;

    /**
     * @var LocalFilesystem
     */
    protected $src;

    /**
     * @var LocalFilesystem
     */
    protected $test;

    /**
     * @var LocalFilesystem
     */
    protected $nest;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->merged = new MergedFilesystem();
        $this->src = new LocalFilesystem(__DIR__ . '/../../../../src');
        $this->test = new LocalFilesystem(__DIR__ . '/../../../../test');
        $this->nest = new LocalFilesystem(__DIR__ . '/../../../../test');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::mount
     */
    public function testMount()
    {
        $this->merged->mount($this->src, 'lib/php-filesystem/src');
        $this->merged->mount($this->test, 'lib/php-filesystem/test');
        $this->merged->mount($this->nest, 'lib/php-filesystem/test/nest');
        $this->assertEquals($this->merged->mounts(), array
        (
            0 => '/lib/php-filesystem/src',
            1 => '/lib/php-filesystem/test',
            2 => '/lib/php-filesystem/test/nest'
        ));
        return $this->merged;
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::getRoot
     */
    public function testGetRoot()
    {
        $virtualRoot = new VirtualFile('', '/', $this->merged);
        $this->assertEquals($this->merged->getRoot()->getPathname(), $virtualRoot->getPathname());
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::getFile
     * @todo   Implement testGetFile().
     * /
    public function testGetFile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::diskFreeSpace
     * @todo   Implement testDiskFreeSpace().
     * /
    public function testDiskFreeSpace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::diskTotalSpace
     * @todo   Implement testDiskTotalSpace().
     * /
    public function testDiskTotalSpace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
    */

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::glob
     * @todo   Implement testGlob().
     * /
    public function testGlob()
    {
        $root = $this->merged->getRoot();

        var_dump($root->listAll());

        var_dump($this->merged->glob('*'));
    }
    */

   /**
     * @covers bit3\filesystem\merged\MergedFilesystem::getFile
     * @depends testMount
     */
    public function testGetVirtualFile($merged)
    {
        $this->assertEquals(
            $merged->getFile('/lib/php-filesystem'),
            new VirtualFile('/lib', 'php-filesystem', $merged)
        );
    }

    static function recursiveIterate($root, $mode)
    {
        //
        $filesystemIterator = new RecursiveFilesystemIterator($root, $mode);
        $treeIterator = new \RecursiveTreeIterator($filesystemIterator);

        $arrResult = array();
        foreach ($treeIterator as $path) {
            $arrResult[] = $path;
        }
        return $arrResult;
    }

   /**
     * @covers bit3\filesystem\merged\MergedFilesystem::glob
     * @depends testMount
     */
    public function testNest($merged)
    {
        $root = $this->nest->getRoot();
        $arrTest = MergedFilesystemTest::recursiveIterate($root, FilesystemIterator::CURRENT_AS_BASENAME);

        $root = $merged->getFile('/lib/php-filesystem/test/nest');
        $arrNest = MergedFilesystemTest::recursiveIterate($root, FilesystemIterator::CURRENT_AS_BASENAME);

        $this->assertEquals($arrTest, $arrNest);
    }

   /**
     * @covers bit3\filesystem\merged\MergedFilesystem::glob
     * @depends testMount
     */
    public function testTree($merged)
    {
        $root = $merged->getRoot();

        $filesystemIterator = new RecursiveFilesystemIterator($root, FilesystemIterator::CURRENT_AS_BASENAME);
        $treeIterator = new \RecursiveTreeIterator($filesystemIterator);

        foreach ($treeIterator as $path) {
            echo $path . "\n";
        }
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::umount
     * @depends testMount
     * Note: keep this last, as otherwise the umount test will be run before the tests depending on mount are run,
     * causing those to fail.
     */
    public function testUmount($merged)
    {
        $merged->umount('lib/php-filesystem/test/nest');
        $merged->umount('lib/php-filesystem/test');
        $merged->umount('lib/php-filesystem/src');

        $this->assertEquals($merged->mounts(), array());
    }
}
