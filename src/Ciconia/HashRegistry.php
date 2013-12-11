<?php

namespace Ciconia;

use Ciconia\Common\Collection;
use Ciconia\Common\Text;

/**
 * Manages hashes and raw text
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HashRegistry extends Collection
{

    /**
     * Register a string to be hashed
     *
     * @param Text $text The string to be hashed
     *
     * @return string The hashed string
     */
    public function register(Text $text)
    {
        $hash = $this->generateHash($text);
        $this->set($hash, $text);

        return new Text($hash);
    }

    /**
     * Generates a hash
     *
     * @param Text $text The string to be hashed
     *
     * @return string The hashed string
     */
    protected function generateHash(Text $text)
    {
        return '{boundary:md5(' . md5($text) . ')}';
    }

}