<?php
namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * File
 *
 * Reads a version from a file
 */
class File implements ReaderInterface
{
    /**
     * The file
     *
     * @var string
     */
    private $file;

    public function __construct(string $file)
    {
        $this->setFile($file);
    }

    /**
     * {@inheritdoc}
     */
    public function canRead(string $directory): bool
    {
        return is_readable($directory . DIRECTORY_SEPARATOR . $this->getFile());
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $directory)
    {
        $contents = file_get_contents($directory . DIRECTORY_SEPARATOR . $this->getFile());
        if (!$contents) {
            return null;
        }
        return trim($contents);
    }

    /**
     * Get the file
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Set the file
     */
    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
