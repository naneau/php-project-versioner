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

        $versioner = new Versioner(
            $directory,
            array(new MTimeReader('*.txt'))
        );

        // Set the time to now for one  of the files
        $time = time();
        touch($directory . '/DirectoryOne/FileFour.txt', $time);

        $this->assertEquals($time, $versioner->get());
    }

    public function testContents()
    {
        $versioner = new Versioner(
            __DIR__ . '/../projects/finder',
            array(new ContentsReader('*.php'))
        );

        $hash = 'ee53e4';
        $this->assertEquals($hash, $versioner->get());
    }

    public function testEmptyNames()
    {
        $directory = __DIR__ . '/../projects/finder';

        $versioner = new Versioner(
            $directory,
            array(new MTimeReader())
        );

        // Set the time to now for one  of the files
        $time = time();
        touch($directory . '/DirectoryOne/FileFour.txt', $time);

        $this->assertEquals($time, $versioner->get());
    }

    public function testEmptyNamesWithFinder()
    {
        $directory = __DIR__ . '/../projects/finder';

        $finderTxt = new Finder;
        $finderTxt->name('*.txt');
        $versionerTxt = new Versioner(
            $directory,
            array(new MTimeReader(null, $finderTxt))
        );

        $finderPhp = new Finder;
        $finderPhp->name('*.php');
        $versionerPhp = new Versioner(
            $directory,
            array(new MTimeReader(null, $finderPhp))
        );

        $timeThree = time();
        $timeFour = $timeThree - 10;

        touch($directory . '/DirectoryOne/FileThree.php', $timeThree);
        touch($directory . '/DirectoryOne/FileFour.txt', $timeFour);

        $this->assertEquals($timeThree, $versionerPhp->get());
        $this->assertEquals($timeFour, $versionerTxt->get());
    }

    public function testNamesAndFinder()
    {
        $directory = __DIR__ . '/../projects/finder';

        $finder = new Finder;
        $finder->name('*.txt');
        $versioner = new Versioner(
            $directory,
            array(new MTimeReader('*.php', $finder))
        );

        $timeThree = time();
        $timeFour = $timeThree - 10;

        touch($directory . '/DirectoryOne/FileThree.php', $timeThree);
        touch($directory . '/DirectoryOne/FileFour.txt', $timeFour);

        $this->assertEquals($timeThree, $versioner->get());
    }
}
