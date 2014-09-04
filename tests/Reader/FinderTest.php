<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;
use Naneau\ProjectVersioner\Reader\Finder\Contents as ContentsReader;

use Symfony\Component\Finder\Finder;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    public function testMtime()
    {
        $directory = __DIR__ . '/../projects/finder';

        // Set the time to now for one  of the files
        $time = time();
        touch($directory . '/DirectoryOne/FileFour.txt', $time);

        $finder = new Finder;
        $finder->name('*.txt');

        $readers = array(new MTimeReader($finder));

        $versioner = new Versioner($directory, $readers);

        $this->assertEquals($time, $versioner->get());
    }

    public function testContents()
    {
        $directory = __DIR__ . '/../projects/finder';

        $finder = new Finder;
        $finder->name('*.php');

        $readers = array(new ContentsReader($finder));

        $versioner = new Versioner($directory, $readers);

        $hash = 'ee53e4';
        $this->assertEquals($hash, $versioner->get());
    }
}
