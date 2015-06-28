<?php

namespace ESJ\Earley\Recognizer;

use ESJ\Earley\Recognizer\Rule\Entry\Entry;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\ToString;

class Item implements ToString
{
    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var int
     */
    private $entryPosition;

    /**
     * @var int
     */
    private $inputPosition;

    /**
     * @param Rule $rule
     * @param int $entryPosition
     * @param int $inputPosition
     */
    private function __construct(Rule $rule, $entryPosition, $inputPosition)
    {
        $this->rule = $rule;
        $this->entryPosition = $entryPosition;
        $this->inputPosition = $inputPosition;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return int
     */
    public function getEntryPosition()
    {
        return $this->entryPosition;
    }

    /**
     * @return int
     */
    public function getInputPosition()
    {
        return $this->inputPosition;
    }

    /**
     * @return null|Entry
     */
    public function getEntryInRule()
    {
        return $this->rule->getEntryAt($this->entryPosition);
    }

    public function __toString()
    {
        $rule = $this->rule;
        $entryPosition = $this->entryPosition;
        $inputPosition = $this->inputPosition;

        $entries = array_map(function (Entry $entry) {
            return $entry->__toString();
        }, $rule->getEntries());

        if ($entryPosition === count($entries)) {
            $entries[] = '*';
        } else {
            $entries[$entryPosition] = '*' . $entries[$entryPosition];
        }

        return sprintf(
            '%s -> %s (%d)',
            $rule->getName(),
            implode(' ', $entries),
            $inputPosition
        );
    }
}
