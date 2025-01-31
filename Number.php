<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Component\Functions;

final class Number
{
    use Number\_DistanceBetweenPoints;
    use Number\_DistanceInKm;
    use Number\_GreatestCommonDiviser;
    use Number\_IsFloat;
    use Number\_IsInt;
    use Number\_IsPrimal;
    use Number\_IsPrime;
    use Number\_IsPrimeBelow1000;
    use Number\_IsPrimeGmp;
    use Number\_LeastCommonMultiple;
    use Number\_Swap;
}
