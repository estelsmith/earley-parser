<?php

namespace ESJ\Earley\Recognizer;

use ESJ\Earley\ToString;

class State implements ToString
{
    /**
     * @var \ArrayIterator|Set[]
     */
    private $sets;

    public function __construct()
    {
        $this->sets = new \ArrayIterator();
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
