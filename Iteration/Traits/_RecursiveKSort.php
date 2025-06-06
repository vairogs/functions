<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Functions\Iteration\Traits;

use function is_array;
use function ksort;

use const SORT_REGULAR;

trait _RecursiveKSort
{
    public function recursiveKSort(
        array &$array,
        int $flags = SORT_REGULAR,
    ): true {
        static $_helper = null;

        if (null === $_helper) {
            $_helper = new class {
                use _RecursiveKSort;
            };
        }

        foreach ($array as &$v) {
            if (is_array($v)) {
                $_helper->recursiveKSort($v, $flags);
            }
        }

        unset($v);

        return ksort($array, $flags);
    }
}
