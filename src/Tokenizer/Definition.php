<?php

namespace ESJ\Earley\Tokenizer;

class Definition
{
    /**
     * @var bool
     */
    private $discard;

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
     * @param bool $discard
     */
    public function __construct($name, $pattern, $discard = false)
    {
        $this->name = $name;
        $this->pattern = sprintf('/^(%s)/', $pattern);
        $this->discard = $discard;
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

            if (!$this->discard) {
                return new Token($this, $matches[0]);
            }
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
}
