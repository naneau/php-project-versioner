<?php
namespace Naneau\ProjectVersioner\Test\Reader;

use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Commit\Exec as GitCommitExecReader;
use Naneau\ProjectVersioner\Reader\Git\Describe\Exec as GitDescribeExecReader;
use Naneau\ProjectVersioner\Reader\Git\Tag\Exec as GitTagExecReader;

class GitExecTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test reading of latest commit
     */
    public function testShortCommitRead(): void
    {
        $versionOutput = self::execInDir(['git rev-parse --short HEAD']);
        $version = $versionOutput[0];

        $versioner = new Versioner([new GitCommitExecReader]);

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    /**
     * Test reading of latest commit
     */
    public function testLongCommitRead(): void
    {
        $versionOutput = self::execInDir(['git rev-parse HEAD']);
        $version = $versionOutput[0];

        $versioner = new Versioner([new GitCommitExecReader(false)]);

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    /**
     * Test reading of latest commit
     */
    public function testDescribeRead(): void
    {
        $versionOutput = self::execInDir(['git describe']);
        $version = $versionOutput[0];

        $versioner = new Versioner([new GitDescribeExecReader]);

        self::assertEquals($version, $versioner->get(self::getDirectory()));
    }

    public function testTagRead(): void
    {
        $versioner = new Versioner([new GitTagExecReader]);
        self::assertEquals('0.0.2', $versioner->get(self::getDirectory()));
    }

    /**
     * Set up fixtures
     */
    public function setUp(): void
    {
        self::execWithDir(['rm -rf %s', 'mkdir %s']);
        self::execInDir([
            'touch testfile',
            'git init'
        ]);

        // Add commits, with matching tags
        for ($x = 0; $x < 3; $x++) {
            self::execInDir([

                // Contained in tag
                sprintf('touch test.%d', $x),
                sprintf('git add test.%d', $x),
                sprintf('git commit -m "commit %d"', $x),
                sprintf('git tag -a -m "0.0.%1$d" 0.0.%1$d', $x),

                // Not in tag
                sprintf('touch test.%d.notag', $x),
                sprintf('git add test.%d.notag', $x),
                sprintf('git commit -m "commit %d no tag"', $x)
            ]);
        }
    }

    /**
     * Tear down fixtures
     */
    public function tearDown(): void
    {
        self::execWithDir(['rm -rf %s']);
    }

    /**
     * Exec a sert of shell commands
     *
     * @param string[] $cmds
     * @return string[]
     */
    private static function execInDir(array $cmds): array
    {
        foreach ($cmds as $key => $cmd) {
            $cmds[$key] = 'cd %s && ' . $cmd;
        }
        return self::execWithDir($cmds);
    }

    /**
     * Exec a bunch of commands with the test directory given
     *
     * @param string[] $cmds
     * @return string[] output from latest command
     */
    private static function execWithDir(array $cmds): array
    {
        foreach ($cmds as $cmd) {
            $inflectedCmd = sprintf(
                $cmd,
                escapeshellarg(self::getDirectory())
            );

            $output = [];
            $return = 0;
            exec($inflectedCmd, $output, $return);
            if ($return !== 0) {
                throw new \RuntimeException(sprintf(
                    'Could not init git: "%s" returned %d, %s',
                    $inflectedCmd,
                    $return,
                    implode("\n", $output)
                ));
            }
        }

        // Return latest output
        return $output ?? [];
    }

    /**
     * Get git tests directory
     */
    private static function getDirectory(): string
    {
        return __DIR__ . '/../../../../projects/git';
    }
}
