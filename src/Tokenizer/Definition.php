<?php

namespace ESJ\Earley\Tokenizer;

class Definition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $name
     * @param string $pattern
     */
    public function __construct($name, $pattern)
    {
        $this->name = $name;
        $this->pattern = sprintf('/^(%s)/', $pattern);
    }

    /**
     * @param string $input
     * @return null|Token
     */
    public function consume(&$input)
    {
        $regex = $this->pattern;
        $matches = [];

        if (preg_match($regex, $input, $matches)) {
            $input = preg_replace($regex, '', $input, 1);
            return new Token($this, $matches[0]);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}
