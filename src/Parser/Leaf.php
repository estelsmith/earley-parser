<?php

namespace ESJ\Earley\Parser;

use ESJ\Earley\Tokenizer\Token;

class Leaf implements Node
{
    /**
     * @var Token
     */
    private $value;

    /**
     * @param Token $value
     */
    public function __construct(Token $value)
    {
        $this->value = $value;
    }

    /**
     * @return Token
     */
    public function getValue()
    {
        return $this->value;
    }
}
