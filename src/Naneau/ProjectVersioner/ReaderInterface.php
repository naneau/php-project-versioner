<?php
/**
 * ReaderInterface.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner;

/**
 * ReaderInterface
 *
 * Interface for reading
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
interface ReaderInterface
{
    /**
     * Can a directory be versioned using this reader?
     *
     * @param  string $directory directory to read the version from
     * @return bool
     **/
    public function canRead($directory);

    /**
     * Read the version from a directory
     *
     * @param  string $directory directory to read the version from
     * @return string version
     **/
    public function read($directory);
}
