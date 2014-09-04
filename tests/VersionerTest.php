<?php

use Naneau\ProjectVersioner\Versioner;

class VersionerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     **/
    public function testNoDir()
    {
        $versioner = new Versioner('foo');
    }

    /**
     * @expectedException RuntimeException
     **/
    public function testNoReaders()
    {
        $versioner = new Versioner(__DIR__ . '/projects/file');

        $versioner->get();
    }
}
