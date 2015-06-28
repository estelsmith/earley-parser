<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

class LiteralList implements Entry
{
    /**
     * @var Literal[]
     */
    private $literals;

    /**
     * @param Literal[] $literals
     */
    public function __construct(array $literals)
    {
        $this->addLiterals($literals);
    }

    /**
     * @return Literal[]
     */
    public function getLiterals()
    {
        return $this->literals;
    }

    /**
     * @param Literal $literal
     * @return bool
     */
    public function hasLiteral(Literal $literal)
    {
        $literals = $this->literals;

        foreach ($literals as $currentLiteral) {
            if ($literal->equals($currentLiteral)) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        $literals = $this->literals;

        $characters = array_map(function (Literal $literal) {
            return $literal->__toString();
        }, $literals);

        return sprintf(
            '[%s]',
            implode('', $characters)
        );
    }

    /**
     * @param array $literals
     */
    private function addLiterals(array $literals)
    {
        foreach ($literals as $literal) {
            $this->addLiteral($literal);
        }
    }

    /**
     * @param Literal $literal
     */
    private function addLiteral(Literal $literal)
    {
        $this->literals[] = $literal;
    }
}
