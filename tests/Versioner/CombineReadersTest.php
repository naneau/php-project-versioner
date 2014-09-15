<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\File as FileReader;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;


class CombineReadersTest extends \PHPUnit_Framework_TestCase
{
    public function testComposerFirst()
    {
        $readers = array(
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader('VERSION'),
            new FileReader('VERSION')
        );

        $versioner = new Versioner($readers);

        return $this->assertEquals(
            'v2.5.4',
            $versioner->get(
                __DIR__ . '/../projects/composer-file/'
            )
        );
    }

    public function testFileFirst()
    {
        $readers = array(
            new FileReader('VERSION'),
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader('VERSION')
        );

        $versioner = new Versioner($readers);

        return $this->assertEquals(
            '5.4.3',
            $versioner->get(
                __DIR__ . '/../projects/composer-file/'
            )
        );
    }

    public function testComposerFirstWithCombine()
    {
        $readers = array(
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader('VERSION'),
            new FileReader('VERSION')
        );

        $versioner = new Versioner($readers);

        return $this->assertEquals(
            'v2.5.4_aa1f22_5.4.3',
            $versioner->getCombined(
                __DIR__ . '/../projects/composer-file/',
                '_' // use _ for separator
            )
        );
    }

    public function testFileFirstWithCombine()
    {
        $readers = array(
            new FileReader('VERSION'),
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader('VERSION')
        );
        $versioner = new Versioner($readers);

        return $this->assertEquals(
            '5.4.3-v2.5.4-aa1f22',
            $versioner->getCombined(
                __DIR__ . '/../projects/composer-file/'
            )
        );
    }
}
