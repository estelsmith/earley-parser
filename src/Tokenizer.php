<?php

namespace ESJ\Earley;

use ESJ\Earley\Tokenizer\Definition;
use ESJ\Earley\Tokenizer\Token;

class Tokenizer
{
    /**
     * @var Definition[]
     */
    private $definitions = [];

    /**
     * @param Definition[] $definitions
     */
    public function __construct($definitions)
    {
        $this->addDefinitions($definitions);
    }

    /**
     * @param string $input
     * @return Token[]
     */
    public function tokenize($input)
    {
        $definitions = $this->definitions;
        $tokens = [];

        while (strlen($input) > 0) {
            $token = null;

            foreach ($definitions as $definition) {
                $token = $definition->consume($input);

                if ($token) {
                    $tokens[] = $token;
                    break;
                }
            }
        }

        $tokens[] = new Token(new Definition('EOF', '\x00'), chr(0));

        return $tokens;
    }

    /**
     * @param Definition[] $definitions
     */
    private function addDefinitions($definitions)
    {
        foreach ($definitions as $definition) {
            $this->addDefinition($definition);
        }

        $this->addDefinition(new Definition('UNKNOWN', '.'));
    }

    /**
     * @param Definition $definition
     */
    private function addDefinition(Definition $definition)
    {
        $this->definitions[] = $definition;
    }
}
