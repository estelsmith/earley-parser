<?php

namespace ESJ\Earley\Recognizer\Rule;

use ESJ\Earley\Recognizer\Rule\Entry\Entry;
use ESJ\Earley\ToString;

class Rule implements ToString
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
