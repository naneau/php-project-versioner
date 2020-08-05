<?php
namespace Naneau\ProjectVersioner\Test\Reader;

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;
use Naneau\ProjectVersioner\Reader\ComposerJson as ComposerJsonReader;

class ComposerTest extends \PHPUnit\Framework\TestCase
{
    public function testRead(): void
    {
        $directory = __DIR__ . '/../../../../projects/composer';

        $readers = [new ComposerReader];

        $versioner = new Versioner($readers);

        self::assertEquals('aa1f22', $versioner->get($directory));
    }

    public function testPackageRead(): void
    {
        $directory = __DIR__ . '/../../../../projects/composer';

        $readers = [new ComposerPackageReader('symfony/filesystem')];

        $versioner = new Versioner($readers);

        self::assertEquals('v2.5.4', $versioner->get($directory));
    }

    public function testComposerJsonRead(): void
    {
        $directory = __DIR__ . '/../../../../projects/composer';

        $readers = [new ComposerJsonReader];

        $versioner = new Versioner($readers);

        self::assertEquals('1.0.0', $versioner->get($directory));
    }
}
