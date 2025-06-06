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

use Vairogs\Functions\Local;
use Vairogs\Functions\Php;

use function str_shuffle;

trait _Shuffle
{
    public function shuffle(
        string $string,
    ): string {
        static $_helper = null;

        if (null === $_helper) {
            $_helper = new class {
                use Local\Traits\_IsInstalled;
                use Php\Traits\_Randomizer;
            };
        }

        if ($_helper->isInstalled(packages: ['random'])) {
            return $_helper->randomizer()->shuffleBytes(bytes: $string);
        }

        return str_shuffle(string: $string);
    }
}
