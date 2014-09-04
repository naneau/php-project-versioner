<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;

class ComposerTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        $directory = __DIR__ . '/../projects/composer';

        $readers = array(new ComposerReader);

        $versioner = new Versioner($directory, $readers);

        $this->assertEquals('aa1f22', $versioner->get());
    }

    public function testPackageRead()
    {
        $directory = __DIR__ . '/../projects/composer';

        $readers = array(new ComposerPackageReader('symfony/filesystem'));

        $versioner = new Versioner($directory, $readers);

        $this->assertEquals('v2.5.4', $versioner->get());
    }
}
