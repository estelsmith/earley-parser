<?php

namespace ESJ\Earley\Recognizer;

use ESJ\Earley\ToString;

class State implements ToString
{
    /**
     * @var \ArrayIterator|Set[]
     */
    private $sets;

    private $startRuleName;

    /**
     * @param string $startRuleName
     */
    public function __construct($startRuleName)
    {
        $this->sets = new \ArrayIterator();
        $this->startRuleName = $startRuleName;
    }

    /**
     * @param Set $set
     */
    public function addSet(Set $set)
    {
        $this->sets->append($set);
    }

    /**
     * @return \ArrayIterator|Set[]
     */
    public function getSets()
    {
        return $this->sets;
    }

    public function isValid()
    {
        $startRuleName = $this->startRuleName;
        $sets = $this->sets;
        $endSet = $sets[$sets->count() - 1];

        foreach ($endSet->getItems() as $item) {
            $valid = true;

            $valid = $valid && ($item->isComplete());
            $valid = $valid && ($item->getInputPosition() === 0);
            $valid = $valid && ($item->getRule()->getName() === $startRuleName);

            if ($valid) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        /** @var Set[] $sets */
        $sets = $this->sets->getArrayCopy();

        $setsString = '';
        foreach ($sets as $index => $set) {
            $setsString .= sprintf("-- %d --\n", $index);
            $setsString .= $set->__toString() . "\n";
        }

        return $setsString;
    }
}
