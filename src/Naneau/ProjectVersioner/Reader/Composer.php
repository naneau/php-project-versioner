<?php
/**
 * Composer.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * Composer
 *
 * Finds version from composer lock file
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Composer implements ReaderInterface
{
    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        return is_readable($directory . '/composer.lock');
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        return substr(
            md5(file_get_contents($directory . '/composer.lock')),
            0, 6
        );
    }
}
