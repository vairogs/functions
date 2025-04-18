<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Functions\Pagination;

use InvalidArgumentException;

use function ceil;
use function floor;
use function range;
use function sprintf;

final class Pagination
{
    private const int MIN_VISIBLE = 5;
    private int $current;
    private int $indicator = -1;
    private int $total;

    private int $visible;

    public function paginate(
        int $visible,
        int $total,
        int $current,
        int $indicator = -1,
    ): array {
        $this->visible = $visible;
        $this->total = $total;
        $this->current = $current;
        $this->indicator = $indicator;

        $this->checkMinimum();

        return $this->getPaginationData();
    }

    private function checkMinimum(): void
    {
        if ($this->visible < self::MIN_VISIBLE) {
            throw new InvalidArgumentException(message: sprintf('Maximum of number of visible pages (%d) should be at least %d', $this->visible, self::MIN_VISIBLE));
        }
    }

    private function getDataWithSingleOmitted(): array
    {
        $rest = $this->visible - ($this->total - $this->current);
        $omitPagesFrom = (int) ceil(num: $rest / 2);
        $omitPagesTo = $this->current - ($rest - $omitPagesFrom);

        if ($this->hasSingleOmittedNearLast()) {
            $rest = $this->visible - $this->current;
            $omitPagesFrom = ((int) ceil(num: $rest / 2)) + $this->current;
            $omitPagesTo = $this->total - ($this->visible - $omitPagesFrom);
        }

        return [
            ...range(start: 1, end: $omitPagesFrom - 1),
            ...[$this->indicator],
            ...range(start: $omitPagesTo + 1, end: $this->total),
        ];
    }

    private function getDataWithTwoOmitted(): array
    {
        $withoutCurrent = ($this->visible - 1) / 2;

        $visibleLeft = floor(num: $withoutCurrent);
        $visibleRight = ceil(num: $withoutCurrent);

        if ($this->current <= ceil(num: $this->total / 2)) {
            $visibleLeft = ceil(num: $withoutCurrent);
            $visibleRight = floor(num: $withoutCurrent);
        }

        $omitLeftFrom = floor(num: $visibleLeft / 2) + 1;
        $omitRightFrom = ceil(num: $visibleRight / 2) + $this->current;

        return [
            ...range(start: 1, end: $omitLeftFrom - 1),
            ...[$this->indicator],
            ...range(start: $this->current - ($visibleLeft - $omitLeftFrom), end: $omitRightFrom - 1),
            ...[$this->indicator],
            ...range(start: $this->total - ($visibleRight - ($omitRightFrom - $this->current)) + 1, end: $this->total),
        ];
    }

    private function getPaginationData(): array
    {
        $this->validate();

        if ($this->total <= $this->visible) {
            return range(start: 1, end: $this->total);
        }

        if ($this->hasSingleOmitted()) {
            return $this->getDataWithSingleOmitted();
        }

        return $this->getDataWithTwoOmitted();
    }

    private function getSingleBreakpoint(): int
    {
        return (int) floor(num: $this->visible / 2) + 1;
    }

    private function hasSingleOmitted(): bool
    {
        return $this->hasSingleOmittedNearLast() || $this->hasSingleOmittedNearStart();
    }

    private function hasSingleOmittedNearLast(): bool
    {
        return $this->current <= $this->getSingleBreakpoint();
    }

    private function hasSingleOmittedNearStart(): bool
    {
        return $this->current >= $this->total - $this->getSingleBreakpoint() + 1;
    }

    private function validate(): void
    {
        if ($this->total < 1) {
            throw new InvalidArgumentException(message: sprintf('Total number of pages (%d) should not be lower than 1', $this->total));
        }

        if ($this->current < 1) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be lower than 1', $this->current));
        }

        if ($this->current > $this->total) {
            throw new InvalidArgumentException(message: sprintf('Current page (%d) should not be higher than total number of pages (%d)', $this->current, $this->total));
        }

        if ($this->indicator >= 1 && $this->indicator <= $this->total) {
            throw new InvalidArgumentException(message: sprintf('Omitted pages indicator (%d) should not be between 1 and total number of pages (%d)', $this->indicator, $this->total));
        }
    }
}
