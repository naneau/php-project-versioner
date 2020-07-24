<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;

class VersionerTest extends \PHPUnit\Framework\TestCase
{
    public function testNoDir()
    {
        $this->expectException(\InvalidArgumentException::class);

        $versioner = new Versioner(array(new MTimeReader('*.txt')));
        $versioner->get('foo');
    }

    public function testNoReaders()
    {
        $this->expectException(\RuntimeException::class);

        $versioner = new Versioner();

        $versioner->get('foo');
    }
}
