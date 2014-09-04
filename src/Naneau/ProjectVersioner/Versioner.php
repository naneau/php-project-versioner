<?php
/**
 * Versioner.php
 *
 * @package         ProjectVersioner
 * @subpackage      Versioner
 */

namespace Naneau\ProjectVersioner;

use Naneau\ProjectVersioner\ReaderInterface as Reader;

use \RuntimeException;

/**
 * Versioner
 *
 * Fetches the version for a directory
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Versioner
 */
class Versioner
{
    /**
     * Directory
     *
     * @var string
     **/
    private $directory;

    /**
     * The readers
     *
     * @var Reader[]
     **/
    private $readers;

    /**
     * Constructor
     *
     * @param  string   $directory
     * @param  Reader[] $readers
     * @return void
     **/
    public function __construct($directory, array $readers = array())
    {
        $this
            ->setDirectory($directory)
            ->setReaders($readers);
    }

    public function get()
    {
        foreach ($this->getReaders() as $reader) {
            if ($reader->canRead($this->getDirectory())) {
                return $reader->read($this->getDirectory());
            }
        }

        throw new RuntimeException(sprintf(
            'Can not read version from directory "%s"',
            $this->getDirectory()
        ));
    }

    /**
     * Get the directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set the directory
     *
     * @param  string $directory
     * @return parent
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get the set of readers
     *
     * @return Reader[]
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * Set the set of readers
     *
     * @param  Reader[]  $readers
     * @return Versioner
     */
    public function setReaders(array $readers)
    {
        $this->readers = $readers;

        return $this;
    }

    /**
     * Add a reader
     *
     * @param  Reader[]  $readers
     * @return Versioner
     */
    public function addReaders()
    {
        $this->readers[] = $readers;

        return $this;
    }
}
