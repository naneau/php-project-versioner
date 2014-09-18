<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;
use Naneau\ProjectVersioner\Reader\File as FileReader;

class VersionerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     **/
    public function testNoDir()
    {
        $versioner = new Versioner(array(new MTimeReader('*.txt')));
        $versioner->get('foo');
    }

    /**
     * @expectedException RuntimeException
     **/
    public function testNoReaders()
    {
        $versioner = new Versioner();

        $versioner->get('foo');
    }

    public function testHasVersion()
    {
        $versioner = new Versioner(array(
            new FileReader('VERSION')
        ));

        $this->assertTrue($versioner->has(
            __DIR__ . '/projects/file'
        ));
    }

    public function testHasNoVersion()
    {
        $versioner = new Versioner(array(
            new FileReader('VERSION')
        ));

        $this->assertFalse($versioner->has(
            __DIR__ . '/projects/no-version'
        ));
    }
}
