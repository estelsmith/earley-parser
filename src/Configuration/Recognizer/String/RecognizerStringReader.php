<?php

namespace ESJ\Earley\Configuration\Recognizer\String;

use ESJ\Earley\Configuration\Recognizer\Visitor\ParseTreeVisitor;
use ESJ\Earley\Parser;
use ESJ\Earley\Recognizer;
use ESJ\Earley\Recognizer\Rule\Entry\Reference;
use ESJ\Earley\Recognizer\Rule\Entry\TokenReference;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\Recognizer\Rule\RuleCollection;
use ESJ\Earley\Tokenizer;
use ESJ\Earley\Tokenizer\Definition;

class RecognizerStringReader
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
     * @return static
     */
    public static function create()
    {
        return new static(new ParseTreeVisitor());
    }

    /**
     * @param string $input
     * @param string $startRuleName
     * @return Recognizer
     * @throws \Exception
     */
    public function readInput($input, $startRuleName)
    {
        $input .= "\n";
        // Fun story. Earley parsers don't like empty rules, so
        // %TOKEN_EOL currently has to exist at the end of the input
        // in order for recognition to happen.

        $recognizer = new Recognizer($this->rules, static::START_RULE_NAME);
        $parser = new Parser();
        $tokens = $this->tokenizer->tokenize($input);

        $state = $recognizer->recognize($tokens);
        $parseTree = $parser->parse($state, $tokens);

        return $this->visitor->dispatch($parseTree, $startRuleName);
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
                new Reference('ruleparts'),
                new TokenReference('TOKEN_EOL')
            ]),
            new Rule('ruleparts', [
                new Reference('ruleparts'),
                new Reference('rulepart')
            ]),
            new Rule('ruleparts', [
                new Reference('rulepart')
            ]),
            new Rule('rulepart', [
                new Reference('reference')
            ]),
            new Rule('rulepart', [
                new Reference('token')
            ]),
            new Rule('reference', [
                new TokenReference('TOKEN_IDENTIFIER')
            ]),
            new Rule('token', [
                new TokenReference('TOKEN_TOKEN')
            ])
        ]);
    }

    /**
     * @return Tokenizer
     */
    private function createTokenizer()
    {
        return new Tokenizer([
            new Definition('TOKEN_EOL', '\n+'),
            new Definition('TOKEN_SPACE', '\s+', true),
            new Definition('TOKEN_IDENTIFIER', '[a-zA-Z][a-zA-Z0-9_-]+'),
            new Definition('TOKEN_SEPARATOR', '->'),
            new Definition('TOKEN_TOKEN', '%[a-zA-Z][a-zA-Z0-9_-]+')
        ]);
    }
}
