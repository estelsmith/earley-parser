<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

use ESJ\Earley\Tokenizer\Token;

class TokenReference implements Entry
{
    /**
     * @var string
     */
    private $tokenName;

    /**
     * @param string $tokenName
     */
    public function __construct($tokenName)
    {
        $this->tokenName = $tokenName;
    }

    /**
     * @return string
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function matchesToken(Token $token)
    {
        return $token->getDefinition()->getName() === $this->tokenName;
    }

    public function __toString()
    {
        return '%' . $this->tokenName;
    }
}
