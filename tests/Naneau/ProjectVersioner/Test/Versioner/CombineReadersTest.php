<?php
namespace Naneau\ProjectVersioner\Test\Versioner;

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\File as FileReader;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;

class CombineReadersTest extends \PHPUnit\Framework\TestCase
{
    public function testComposerFirst(): void
    {
        $readers = [
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader(),
            new FileReader('VERSION')
        ];

        $versioner = new Versioner($readers);

        self::assertEquals(
            'v2.5.4',
            $versioner->get(
                __DIR__ . '/../../../../projects/composer-file/'
            )
        );
    }

    public function testFileFirst(): void
    {
        $readers = [
            new FileReader('VERSION'),
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader()
        ];

        $versioner = new Versioner($readers);

        self::assertEquals(
            '5.4.3',
            $versioner->get(
                __DIR__ . '/../../../../projects/composer-file/'
            )
        );
    }

    public function testComposerFirstWithCombine(): void
    {
        $readers = [
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader(),
            new FileReader('VERSION')
        ];

        $versioner = new Versioner($readers);

        self::assertEquals(
            'v2.5.4_aa1f22_5.4.3',
            $versioner->getCombined(
                __DIR__ . '/../../../../projects/composer-file/',
                '_' // use _ for separator
            )
        );
    }

    public function testFileFirstWithCombine(): void
    {
        $readers = [
            new FileReader('VERSION'),
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader()
        ];
        $versioner = new Versioner($readers);

        self::assertEquals(
            '5.4.3-v2.5.4-aa1f22',
            $versioner->getCombined(
                __DIR__ . '/../../../../projects/composer-file/'
            )
        );
    }

    public function testHasAVersion(): void
    {
        $versioner = new Versioner;

        // This one should have a version
        $versioner->setReaders(
            [new FileReader('VERSION')]
        );
        self::assertTrue(
            $versioner->has(__DIR__ . '/../../../../projects/composer-file/')
        );

        // Should not have a version
        $versioner->setReaders(
            [new FileReader('FOO')]
        );
        self::assertFalse(
            $versioner->has(__DIR__ . '/../../../../projects/composer-file/')
        );
    }
}
