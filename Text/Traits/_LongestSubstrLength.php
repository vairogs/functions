<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Functions\Text\Traits;

use function array_key_exists;
use function max;
use function strlen;

trait _LongestSubstrLength
{
    public function longestSubstrLength(
        string $string,
    ): int {
        $result = $start = 0;
        $chars = [];

        for ($i = 0, $len = strlen(string: $string); $i < $len; $i++) {
            if (array_key_exists($string[$i], $chars)) {
                $start = max($start, $chars[$string[$i]] + 1);
            }

            $result = max($result, $i - $start + 1);
            $chars[$string[$i]] = $i;
        }

        return $result;
    }
}
