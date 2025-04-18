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

use function mb_strrpos;
use function mb_substr;

trait _LastPart
{
    public function lastPart(
        string $text,
        string $delimiter,
    ): string {
        return false === ($idx = mb_strrpos(haystack: $text, needle: $delimiter)) ? $text : mb_substr(string: $text, start: $idx + 1);
    }
}
