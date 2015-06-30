<?php

namespace ESJ\Earley\Tokenizer;

class Token
{
    /**
     * @var Definition
     */
    private $definition;

    /**
     * @var string
     */
    private $value;

    /**
     * @param Definition $definition
     * @param string $value
     */
    public function __construct(Definition $definition, $value)
    {
        $this->definition = $definition;
        $this->value = $value;
    }

    /**
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
