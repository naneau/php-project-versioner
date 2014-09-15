<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Exec as ExecGitReader;

use \RuntimeException;

class GitTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        $directory = __DIR__ . '/../projects/git';

        $version = $this->initGit($directory);

        $versioner = new Versioner($directory, array(new ExecGitReader));

        $this->assertEquals($version, $versioner->get());
    }

    /**
     * Init a git repo in a directory
     *
     * @return void
     **/
    public function initGit($directory)
    {
        // Initialize commands
        $init = array(
            'rm -rf %s',
            'mkdir %s',
            'cd %s && touch testfile',
            'cd %s && git init',
            'cd %s && git add testfile',
            'cd %s && git commit -m "test commit"'
        );

        foreach($init as $initCmd) {
            $cmd = sprintf(
                $initCmd,
                escapeshellarg($directory)
            );
            $output = array();
            $return = 0;
            exec($cmd, $output, $return);
            if ($return !== 0) {
                throw new RuntimeException(sprintf(
                    'Could not init git: "%s" returned %d, %s',
                    $cmd,
                    $return,
                    implode("\n", $output)
                ));
            }

        }

        // Fetch version
        $commitCmd = sprintf(
            'cd %s && git rev-parse --short head',
            escapeshellarg($directory)
        );
        $output = array();
        $return = 0;
        exec($commitCmd, $output, $return);
        if ($return !== 0) {
            throw new RuntimeException('Could not fetch version');
        }

        return $output[0];
    }
}
