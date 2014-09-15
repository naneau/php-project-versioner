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
use \InvalidArgumentException;

/**
 * Versioner
 *
 * Uses a set of readers to fetch versions from directories
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Versioner
 */
class Versioner
{
    /**
     * The readers
     *
     * @var Reader[]
     **/
    private $readers;

    /**
     * Constructor
     *
     * @param  Reader[] $readers
     * @return void
     **/
    public function __construct(array $readers = array())
    {
        $this->setReaders($readers);
    }

    /**
     * Get the version for a directory
     *
     * @param string $directory
     * @return string
     **/
    public function get($directory)
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
