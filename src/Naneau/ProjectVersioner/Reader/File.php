<?php
/**
 * File.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * File
 *
 * Reads a version from a file
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class File implements ReaderInterface
{
    /**
     * The file
     *
     * @var string
     **/
    private $file;

    /**
     * Constructor
     *
     * @param  string $file version file
     * @return void
     **/
    public function __construct($file)
    {
        $this->setFile($file);
    }

    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        return is_readable($directory . DIRECTORY_SEPARATOR . $this->getFile());
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        return trim(file_get_contents($directory . DIRECTORY_SEPARATOR . $this->getFile()));
    }

    /**
     * Get the file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the file
     *
     * @param  string $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

}
