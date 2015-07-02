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

    public function __toString()
    {
        $header = 'leaf';
        $value = $this->value->getValue();

        if ($value === chr(0)) {
            $value = '%EOF';
            $header = 'eof_' . $header;
        }

        return sprintf('%s -> %s', $header, $value);
    }
}
