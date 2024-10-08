<?php declare(strict_types = 1);

/*
 * This file is part of the Vairogs package.
 *
 * (c) Dāvis Zālītis (k0d3r1s) <davis@vairogs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vairogs\Component\Functions\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use stdClass;
use Vairogs\Component\Functions\Text;

use function count;
use function property_exists;

class SimpleObjectCollection implements Countable, IteratorAggregate, ArrayAccess, Contracts\SimpleCollection
{
    private object $elements;

    public function __construct(
        ?object $elements = null,
    ) {
        $this->elements = $elements ?? new stdClass();
    }

    public function add(
        mixed $element,
    ): bool {
        $this->elements->{$this->generateKey()} = $element;

        return true;
    }

    public function clear(): void
    {
        $this->elements = new stdClass();
    }

    public function contains(
        mixed $element,
    ): bool {
        foreach ($this->elements as $storedElement) {
            if ($storedElement === $element) {
                return true;
            }
        }

        return false;
    }

    public function containsKey(
        int|string $key,
    ): bool {
        return property_exists($this->elements, (string) $key);
    }

    public function count(): int
    {
        return count($this->toArray());
    }

    public function exists(
        callable $predicate,
    ): bool {
        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                return true;
            }
        }

        return false;
    }

    public function filter(
        callable $predicate,
    ): self {
        $filteredElements = new stdClass();

        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                $filteredElements->{$key} = $element;
            }
        }

        return new self($filteredElements);
    }

    public function forAll(
        callable $predicate,
    ): bool {
        foreach ($this->elements as $key => $element) {
            if (!$predicate($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function get(
        int|string $key,
    ): mixed {
        return $this->elements->{(string) $key} ?? null;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }

    public function indexOf(
        mixed $element,
    ): string|false {
        foreach ($this->elements as $key => $storedElement) {
            if ($storedElement === $element) {
                return $key;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return [] === $this->toArray();
    }

    public function map(
        callable $func,
    ): self {
        $mappedElements = new stdClass();

        foreach ($this->elements as $key => $element) {
            $mappedElements->{$key} = $func($element);
        }

        return new self($mappedElements);
    }

    public function offsetExists(
        mixed $offset,
    ): bool {
        return $this->containsKey((string) $offset);
    }

    public function offsetGet(
        mixed $offset,
    ): mixed {
        return $this->get((string) $offset);
    }

    public function offsetSet(
        mixed $offset,
        mixed $value,
    ): void {
        $this->set((string) $offset, $value);
    }

    public function offsetUnset(
        mixed $offset,
    ): void {
        $this->remove((string) $offset);
    }

    public function partition(
        callable $predicate,
    ): array {
        $matches = new stdClass();
        $noMatches = new stdClass();

        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                $matches->{$key} = $element;
            } else {
                $noMatches->{$key} = $element;
            }
        }

        return [new self($matches), new self($noMatches)];
    }

    public function remove(
        int|string $key,
    ): mixed {
        if ($this->containsKey($key)) {
            $removedElement = $this->elements->{(string) $key};
            unset($this->elements->{(string) $key});

            return $removedElement;
        }

        return null;
    }

    public function removeElement(
        mixed $element,
    ): bool {
        foreach ($this->elements as $key => $storedElement) {
            if ($storedElement === $element) {
                unset($this->elements->{$key});

                return true;
            }
        }

        return false;
    }

    public function set(
        int|string $key,
        mixed $value,
    ): void {
        $this->elements->{(string) $key} = $value;
    }

    public function slice(
        int $offset,
        ?int $length = null,
    ): self {
        $slicedElements = new stdClass();
        $currentOffset = 0;
        $added = 0;

        foreach ($this->elements as $key => $element) {
            if ($currentOffset >= $offset && (null === $length || $added < $length)) {
                $slicedElements->{$key} = $element;
                $added++;
            }

            $currentOffset++;

            if (null !== $length && $added >= $length) {
                break;
            }
        }

        return new self($slicedElements);
    }

    public function toArray(): array
    {
        return (array) $this->elements;
    }

    private function generateKey(): string
    {
        static $_helper = null;

        if (null === $_helper) {
            $_helper = new class {
                use Text\_UniqueId;
            };
        }

        return $_helper->uniqueId(8);
    }
}
