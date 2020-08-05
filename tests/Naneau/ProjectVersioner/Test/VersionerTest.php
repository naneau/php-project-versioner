<?php
namespace Naneau\ProjectVersioner\Test;

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;

class VersionerTest extends \PHPUnit\Framework\TestCase
{
    public function testNoDir(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $versioner = new Versioner([new MTimeReader('*.txt')]);
        $versioner->get('foo');
    }

    public function testNoReaders(): void
    {
        $this->expectException(\RuntimeException::class);

        $versioner = new Versioner();

        $versioner->get('foo');
    }
}
