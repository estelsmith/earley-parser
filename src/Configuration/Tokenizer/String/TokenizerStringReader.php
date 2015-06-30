<?php

namespace ESJ\Earley\Configuration\Tokenizer\String;

use ESJ\Earley\Configuration\Tokenizer\Visitor\ParseTreeVisitor;
use ESJ\Earley\Parser;
use ESJ\Earley\Recognizer;
use ESJ\Earley\Recognizer\Rule\Entry\Reference;
use ESJ\Earley\Recognizer\Rule\Entry\TokenReference;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\Recognizer\Rule\RuleCollection;
use ESJ\Earley\Tokenizer;
use ESJ\Earley\Tokenizer\Definition;

class TokenizerStringReader
{
    const START_RULE_NAME = 'program';

    /**
     * @var RuleCollection
     */
    private $rules;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var ParseTreeVisitor
     */
    private $visitor;

    /**
     * @param ParseTreeVisitor $visitor
     */
    public function __construct(ParseTreeVisitor $visitor)
    {
        $this->visitor = $visitor;

        $this->rules = $this->createEarleyRules();
        $this->tokenizer = $this->createTokenizer();
    }

    /**
     * @param string $input
     * @return Tokenizer
     * @throws \Exception
     */
    public function readInput($input)
    {
        $recognizer = new Recognizer($this->rules, static::START_RULE_NAME);
        $parser = new Parser();
        $tokens = $this->tokenizer->tokenize($input);

        $state = $recognizer->recognize($tokens);
        $parseTree = $parser->parse($state, $tokens);

        return $this->visitor->dispatch($parseTree);
    }

    /**
     * @return RuleCollection
     */
    private function createEarleyRules()
    {
        return new RuleCollection([
            new Rule('program', [
                new Reference('ruleset'),
                new TokenReference('EOF')
            ]),
            new Rule('ruleset', [
                new Reference('ruleset'),
                new Reference('rule')
            ]),
            new Rule('ruleset', [
                new Reference('rule')
            ]),
            new Rule('rule', [
                new TokenReference('TOKEN_IDENTIFIER'),
                new TokenReference('TOKEN_SEPARATOR'),
                new TokenReference('TOKEN_PATTERN')
            ])
        ]);
    }

    /**
     * @return Tokenizer
     */
    private function createTokenizer()
    {
        return new Tokenizer([
            new Definition('TOKEN_SPACE', '\s+', true),
            new Definition('TOKEN_IDENTIFIER', '[a-zA-Z][a-zA-Z0-9_-]+'),
            new Definition('TOKEN_SEPARATOR', '->'),
            new Definition('TOKEN_PATTERN', '\/(.+)\/')
        ]);
    }
}
