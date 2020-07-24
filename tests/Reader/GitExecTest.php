<?php

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Commit\Exec as GitCommitExecReader;
use Naneau\ProjectVersioner\Reader\Git\Describe\Exec as GitDescribeExecReader;
use Naneau\ProjectVersioner\Reader\Git\Tag\Exec as GitTagExecReader;

class GitExecTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test reading of latest commit
     *
     * @return void
     **/
    public function testShortCommitRead()
    {
        $versionOutput = self::execInDir(array('git rev-parse --short HEAD'));
        $version = $versionOutput[0];

        $versioner = new Versioner(array(new GitCommitExecReader));

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    /**
     * Test reading of latest commit
     *
     * @return void
     **/
    public function testLongCommitRead()
    {
        $versionOutput = self::execInDir(array('git rev-parse HEAD'));
        $version = $versionOutput[0];

        $versioner = new Versioner(array(new GitCommitExecReader(false)));

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    /**
     * Test reading of latest commit
     *
     * @return void
     **/
    public function testDescribeRead()
    {
        $versionOutput = self::execInDir(array('git describe'));
        $version = $versionOutput[0];

        $versioner = new Versioner(array(new GitDescribeExecReader));

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    public function testTagRead()
    {
        $versioner = new Versioner(array(new GitTagExecReader));
        self::assertEquals('0.0.2', $versioner->get(self::getDirectory()));
    }

    /**
     * Set up fixtures
     *
     * @return void
     **/
    public function setUp(): void
    {
        self::execWithDir(array('rm -rf %s', 'mkdir %s'));
        self::execInDir(array(
            'touch testfile',
            'git init'
        ));

        // Add commits, with matching tags
        for ($x = 0; $x < 3; $x++) {
            self::execInDir(array(

                // Contained in tag
                sprintf('touch test.%d', $x),
                sprintf('git add test.%d', $x),
                sprintf('git commit -m "commit %d"', $x),
                sprintf('git tag -a -m "0.0.%1$d" 0.0.%1$d', $x),

                // Not in tag
                sprintf('touch test.%d.notag', $x),
                sprintf('git add test.%d.notag', $x),
                sprintf('git commit -m "commit %d no tag"', $x)
            ));
        }
    }

    /**
     * Tear down fixtures
     *
     * @return void
     **/
    public function tearDown(): void
    {
        self::execWithDir(array('rm -rf %s'));
    }

    /**
     * Exec a sert of shell commands
     *
     * @return array
     **/
    private static function execInDir(array $cmds)
    {
        foreach($cmds as $key => $cmd) {
            $cmds[$key] = 'cd %s && ' . $cmd;
        }
        return self::execWithDir($cmds);
    }

    /**
     * Exec a bunch of commands with the test directory given
     *
     * @param array $cmds
     * @return array output from latest command
     **/
    private static function execWithDir(array $cmds)
    {
        foreach($cmds as $cmd) {
            $inflectedCmd = sprintf(
                $cmd,
                escapeshellarg(self::getDirectory())
            );

            $output = array();
            $return = 0;
            exec($inflectedCmd, $output, $return);
            if ($return !== 0) {
                throw new RuntimeException(sprintf(
                    'Could not init git: "%s" returned %d, %s',
                    $inflectedCmd,
                    $return,
                    implode("\n", $output)
                ));
            }
        }

        // Return latest output
        return $output;
    }

    /**
     * Get git tests directory
     *
     * @return string
     **/
    private static function getDirectory()
    {
        return __DIR__ . '/../projects/git';
    }
}
