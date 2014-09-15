<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;

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
}
