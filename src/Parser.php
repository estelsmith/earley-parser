<?php

namespace ESJ\Earley;

use ESJ\Earley\Parser\Leaf;
use ESJ\Earley\Parser\Tree;
use ESJ\Earley\Recognizer\Item;
use ESJ\Earley\Recognizer\Rule\Entry\LiteralList;
use ESJ\Earley\Recognizer\Rule\Entry\Reference;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\Recognizer\State;

class Parser
{
    /**
     * @param State $state
     * @param string $input
     * @return Tree
     */
    public function parse(State $state, $input)
    {
        $finalItem = $state->getFinalItem();
        $inputData = str_split($input);
        $completedItems = $this->getCompletedItems($state);

        return $this->buildTree($completedItems, $inputData, $finalItem->getRule());
    }

    /**
     * @param State $state
     * @return Item[]
     */
    private function getCompletedItems(State $state)
    {
        $finalItem = $state->getFinalItem();
        $items = [];

        foreach ($state->getSets() as $set) {
            foreach ($set->getItems() as $item) {
                if ($item->isComplete() && $item !== $finalItem) {
                    $items[] = $item;
                }
            }
        }

        return array_reverse($items);
    }

    /**
     * @param Item[] $items
     * @param string[] $inputData
     * @param Rule $rule
     * @return Tree
     */
    private function buildTree(&$items, &$inputData, Rule $rule)
    {
        $result = new Tree($rule);
        $children = [];

        $entries = array_reverse($rule->getEntries());

        foreach ($entries as $entry) {
            $class = get_class($entry);

            switch ($class) {
                case Reference::class:
                    $item = $this->consumeItemMatchingRule($items, $entry->getRuleName());
                    $children[] = $this->buildTree($items, $inputData, $item->getRule());
                    break;
                case LiteralList::class:
                    $children[] = new Leaf(array_pop($inputData));
                    break;
                default:
                    echo 'BLERGH!!!!' . "\n";
                    break;
            }
        }

        $children = array_reverse($children);
        foreach ($children as $child) {
            $result->addChild($child);
        }

        return $result;

    }

    /**
     * @param Item[] $items
     * @param string $ruleName
     * @return null|Item
     */
    private function consumeItemMatchingRule(&$items, $ruleName)
    {
        foreach ($items as $item) {
            if ($item->getRule()->getName() === $ruleName) {
                $this->removeItem($items, $item);
                return $item;
            }
        }

        return null;
    }

    /**
     * @param Item[] $items
     * @param Item $item
     */
    private function removeItem(&$items, Item $item)
    {
        foreach ($items as $index => $listItem) {
            if ($listItem === $item) {
                unset($items[$index]);
                break;
            }
        }
    }
}
