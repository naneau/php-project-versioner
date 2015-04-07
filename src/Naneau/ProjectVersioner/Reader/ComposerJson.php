<?php
/**
 * ComposerJson.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

use \RuntimeException;
use \stdClass;

/**
 * Finds the "version" from the composer.json file
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class ComposerJson implements ReaderInterface
{
    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        return is_readable($directory . '/composer.json');
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        $json = @file_get_contents($directory . '/composer.json');
        if ($json === false) {
            return false;
        }

        $json = json_decode($json);
        if (!($json instanceof stdClass) || empty($json->version)) {
            return false;
        } 

        return trim($json->version);        
    }
}
