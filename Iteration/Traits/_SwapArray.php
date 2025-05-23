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

trait _SwapArray
{
    public function swapArray(
        array &$array,
        mixed $foo,
        mixed $bar,
    ): void {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        [$array[$foo], $array[$bar]] = [$array[$bar], $array[$foo]];
    }
}
