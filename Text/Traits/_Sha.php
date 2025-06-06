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

use function base64_encode;
use function hash;
use function round;
use function rtrim;
use function substr;

trait _Sha
{
    public function sha(
        string $text,
        int $bits = 256,
    ): string {
        $hash = substr(string: hash(algo: 'sha' . $bits, data: $text, binary: true), offset: 0, length: (int) round(num: $bits / 16));

        return strtr(rtrim(string: base64_encode(string: $hash), characters: '='), from: '+/', to: '-_');
    }
}
