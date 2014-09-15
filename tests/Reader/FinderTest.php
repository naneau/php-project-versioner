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

        $versioner = new Versioner(array(new MTimeReader('*.txt')));

        // Set the time to now for one  of the files
        $time = time();
        touch($directory . '/DirectoryOne/FileFour.txt', $time);

        $this->assertEquals($time, $versioner->get($directory));
    }

    public function testContents()
    {
        $versioner = new Versioner(array(new ContentsReader('*.php')));

        $hash = 'ee53e4';
        $this->assertEquals(
            $hash,
            $versioner->get(
                __DIR__ . '/../projects/finder'
            )
        );
    }

    public function testEmptyNames()
    {
        $directory = __DIR__ . '/../projects/finder';

        $versioner = new Versioner(array(new MTimeReader()));

        // Set the time to now for one  of the files
        $time = time();
        touch($directory . '/DirectoryOne/FileFour.txt', $time);

        $this->assertEquals($time, $versioner->get($directory));
    }

    public function testEmptyNamesWithFinder()
    {
        $directory = __DIR__ . '/../projects/finder';

        $finderTxt = new Finder;
        $finderTxt->name('*.txt');
        $versionerTxt = new Versioner(array(new MTimeReader(null, $finderTxt)));

        $finderPhp = new Finder;
        $finderPhp->name('*.php');
        $versionerPhp = new Versioner(array(new MTimeReader(null, $finderPhp)));

        $timeThree = time();
        $timeFour = $timeThree - 10;

        touch($directory . '/DirectoryOne/FileThree.php', $timeThree);
        touch($directory . '/DirectoryOne/FileFour.txt', $timeFour);

        $this->assertEquals($timeThree, $versionerPhp->get($directory));
        $this->assertEquals($timeFour, $versionerTxt->get($directory));
    }

    public function testNamesAndFinder()
    {
        $directory = __DIR__ . '/../projects/finder';

        $finder = new Finder;
        $finder->name('*.txt');
        $versioner = new Versioner(array(new MTimeReader('*.php', $finder)));

        $timeThree = time();
        $timeFour = $timeThree - 10;

        touch($directory . '/DirectoryOne/FileThree.php', $timeThree);
        touch($directory . '/DirectoryOne/FileFour.txt', $timeFour);

        $this->assertEquals($timeThree, $versioner->get($directory));
    }
}
