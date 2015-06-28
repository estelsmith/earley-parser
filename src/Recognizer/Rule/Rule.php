<?php

namespace ESJ\Earley\Recognizer\Rule;

use ESJ\Earley\Recognizer\Rule\Entry\Entry;
use ESJ\Earley\ToString;

class Rule implements \IteratorAggregate, ToString
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Entry[]
     */
    private $entries = [];

    /**
     * @param string $name
     * @param Entry[] $entries
     */
    public function __construct($name, array $entries)
    {
        $this->name = (string)$name;
        $this->addEntries($entries);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param int $index
     * @return null|Entry
     */
    public function getEntryAt($index)
    {
        $result = null;
        $entries = $this->entries;

        if (array_key_exists($index, $entries)) {
            $result = $entries[$index];
        }

        return $result;
    }

    public function __toString()
    {
        $entries = array_map(function (Entry $entry) {
            return $entry->__toString();
        }, $this->entries);

        return sprintf(
            '%s -> %s',
            $this->name,
            implode(' ', $entries)
        );
    }

    /**
     * @param array $entries
     */
    private function addEntries(array $entries)
    {
        foreach ($entries as $entry) {
            $this->addEntry($entry);
        }
    }

    /**
     * @param Entry $entry
     */
    private function addEntry(Entry $entry)
    {
        $this->entries[] = $entry;
    }
}
