<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

class Literal implements Entry
{
    /**
     * @var string
     */
    private $literal;

    /**
     * @param string $literal
     */
    public function __construct($literal)
    {
        $this->literal = (string)$literal;
    }

    /**
     * @return string
     */
    public function getLiteral()
    {
        return $this->literal;
    }

    /**
     * @param Literal $literal
     * @return bool
     */
    public function equals(Literal $literal)
    {
        return $this->__toString() == $literal->__toString();
    }

    public function __toString()
    {
        return $this->literal;
    }
}
