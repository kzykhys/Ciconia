<?php

namespace Ciconia;

use Ciconia\Common\Collection;
use Ciconia\Common\Text;

/**
 * Manages hash and raw text
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HashRegistry extends Collection
{

    /**
     * @param Text $text
     *
     * @return string
     */
    public function register(Text $text)
    {
        $hash = $this->generateHash($text);
        $this->set($hash, $text);

        return new Text($hash);
    }

    /**
     * @param Text $text
     *
     * @return string
     */
    protected function generateHash(Text $text)
    {
        return '{boundary:md5(' . md5($text) . ')}';
    }

}