<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Component\Functions\Preg;

use function preg_last_error;
use function preg_last_error_msg;
use function preg_replace;

use const PREG_NO_ERROR;

trait _Replace
{
    public static function replace(
        array|string $pattern,
        string $replacement,
        $subject,
        int $limit = -1,
        ?int &$count = null,
    ): string {
        $result = @preg_replace((new class {
            use _AddUtf8Modifier;
        })::addUtf8Modifier($pattern), $replacement, $subject, $limit, $count);
        if (null !== $result && PREG_NO_ERROR === preg_last_error()) {
            return $result;
        }

        $result = @preg_replace((new class {
            use _RemoveUtf8Modifier;
        })::removeUtf8Modifier($pattern), $replacement, $subject, $limit, $count);
        if (null !== $result && PREG_NO_ERROR === preg_last_error()) {
            return $result;
        }

        throw (new class {
            use _NewPregException;
        })::newPregException(preg_last_error(), preg_last_error_msg(), __METHOD__, $pattern);
    }
}