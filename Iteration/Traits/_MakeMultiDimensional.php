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

trait _MakeMultiDimensional
{
    public function makeMultiDimensional(
        array $array,
    ): array {
        static $_helper = null;

        if (null === $_helper) {
            $_helper = new class {
                use _IsMultiDimentional;
            };
        }

        if ($_helper->isMultiDimensional(keys: $array)) {
            return $array;
        }

        return array_map(callback: static fn ($item) => [$item], array: array_values(array: $array));
    }
}
