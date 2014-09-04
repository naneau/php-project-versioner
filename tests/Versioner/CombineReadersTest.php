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

        $versioner = new Versioner(__DIR__ . '/../projects/composer-file/', $readers);

        return $this->assertEquals('v2.5.4', $versioner->get());
    }

    public function testFileFirst()
    {
        $readers = array(
            new FileReader('VERSION'),
            new ComposerPackageReader('symfony/filesystem'),
            new ComposerReader('VERSION')
        );

        $versioner = new Versioner(__DIR__ . '/../projects/composer-file/', $readers);

        return $this->assertEquals('5.4.3', $versioner->get());
    }
}
