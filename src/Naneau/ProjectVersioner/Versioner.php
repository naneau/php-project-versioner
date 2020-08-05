<?php
namespace Naneau\ProjectVersioner;

use Naneau\ProjectVersioner\ReaderInterface as Reader;

use \RuntimeException;

/**
 * Versioner
 *
 * Uses a set of readers to fetch versions from directories
 *
 */
class Versioner
{
    /**
     * The readers
     *
     * @var Reader[]
     */
    private $readers;

    /**
     * Constructor
     *
     * @param  Reader[] $readers
     */
    public function __construct(array $readers = [])
    {
        $this->setReaders($readers);
    }

    /**
     * Get the version for a directory
     *
     * @return string|int|null
     */
    public function get(string $directory)
    {
        foreach ($this->getReaders() as $reader) {
            if ($reader->canRead($directory)) {
                return $reader->read($directory);
            }
        }

        throw new RuntimeException(sprintf(
            'Can not read version from directory "%s"',
            $directory
        ));
    }

    /**
     * Get the version for a directory
     *
     * Combining the output of all writers using the given separator
     *
     * Version will be considered "found" if at least one versioner returns
     * output.
     */
    public function getCombined(string $directory, string $separator = '-'): string
    {
        $found = [];
        foreach ($this->getReaders() as $reader) {
            if ($reader->canRead($directory)) {
                $found[] = $reader->read($directory);
            }
        }

        if (count($found) === 0) {
            throw new RuntimeException(sprintf(
                'Can not read version from directory "%s"',
                $directory
            ));
        }

        return implode($separator, $found);
    }

    /**
     * Does a directory have a version?
     */
    public function has(string $directory): bool
    {
        foreach ($this->getReaders() as $reader) {
            if ($reader->canRead($directory)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the set of readers
     *
     * @return Reader[]
     */
    public function getReaders(): array
    {
        return $this->readers;
    }

    /**
     * Set the set of readers
     *
     * @param Reader[] $readers
     */
    public function setReaders(array $readers): self
    {
        $this->readers = $readers;

        return $this;
    }

    /**
     * Add a reader
     */
    public function addReader(Reader $reader): self
    {
        $this->readers[] = $reader;

        return $this;
    }
}
