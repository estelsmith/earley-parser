<?php

namespace ESJ\Earley\Recognizer;

use ESJ\Earley\ToString;

class Set implements ToString
{
    /**
     * @var \ArrayIterator|Item[]
     */
    private $items;

    public function __construct()
    {
        $this->items = new \ArrayIterator();
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        if (!$this->hasItem($item)) {
            $this->items->append($item);
        }
    }

    /**
     * @return \ArrayIterator|Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function hasItem(Item $item)
    {
        /** @var \ArrayIterator|Item[] $items */
        $items = new \ArrayIterator($this->items->getArrayCopy());

        foreach ($items as $currentItem) {
            $matches = true;

            $matches = $matches && ($item->getRule() === $currentItem->getRule());
            $matches = $matches && ($item->getEntryPosition() === $currentItem->getEntryPosition());
            $matches = $matches && ($item->getInputPosition() === $currentItem->getInputPosition());

            if ($matches) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        $items = $this->items->getArrayCopy();

        $itemStrings = array_map(function (Item $item) {
            return $item->__toString() . "\n";
        }, $items);

        return implode($itemStrings);
    }
}
