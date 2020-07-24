<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;
use Naneau\ProjectVersioner\Reader\ComposerJson as ComposerJsonReader;

class ComposerTest extends \PHPUnit\Framework\TestCase
{
    public function testRead()
    {
        $directory = __DIR__ . '/../projects/composer';

        $readers = array(new ComposerReader);

        $versioner = new Versioner($readers);

        self::assertEquals('aa1f22', $versioner->get($directory));
    }

    public function testPackageRead()
    {
        $directory = __DIR__ . '/../projects/composer';

        $readers = array(new ComposerPackageReader('symfony/filesystem'));

        $versioner = new Versioner($readers);

        self::assertEquals('v2.5.4', $versioner->get($directory));
    }

    public function testComposerJsonRead()
    {
        $directory = __DIR__ . '/../projects/composer';

        $readers = array(new ComposerJsonReader);
        $versioner = new Versioner($readers);

        self::assertEquals('1.0.0', $versioner->get($directory));
    }
}
