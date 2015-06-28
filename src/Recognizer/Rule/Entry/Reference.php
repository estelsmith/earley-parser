<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

class Reference implements Entry
{
    /**
     * @var string
     */
    private $ruleName;

    /**
     * @param string $ruleName
     */
    public function __construct($ruleName)
    {
        $this->ruleName = (string)$ruleName;
    }

    /**
     * @return string
     */
    public function getRuleName()
    {
        return $this->ruleName;
    }

    public function __toString()
    {
        return $this->ruleName;
    }
}
