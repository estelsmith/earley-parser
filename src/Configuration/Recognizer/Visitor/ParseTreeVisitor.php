<?php

namespace ESJ\Earley\Configuration\Recognizer\Visitor;

use ESJ\Earley\Parser\Leaf;
use ESJ\Earley\Parser\Tree;
use ESJ\Earley\Recognizer;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\Recognizer\Rule\RuleCollection;
use ESJ\Earley\Recognizer\Rule\Entry\Entry;
use ESJ\Earley\Recognizer\Rule\Entry\Reference;
use ESJ\Earley\Recognizer\Rule\Entry\TokenReference;

class ParseTreeVisitor
{
    /**
     * @param Tree $tree
     * @param string $startRuleName
     * @return Recognizer
     */
    public function dispatch(Tree $tree, $startRuleName)
    {
        return new Recognizer(
            $this->visit($tree),
            $startRuleName
        );
    }

    private function visit($thing)
    {
        $class = get_class($thing);
        $class = explode('\\', $class);
        $class = array_pop($class);

        $method = sprintf('visit%s', $class);

        return call_user_func([$this, $method], $thing);
    }

    private function visitTree(Tree $tree)
    {
        $ruleName = $tree->getRule()->getName();
        $method = sprintf('walk%s', ucfirst(strtolower($ruleName)));

        return call_user_func([$this, $method], $tree);
    }

    private function visitLeaf(Leaf $leaf)
    {
        return $leaf->getValue()->getValue();
    }

    private function walkProgram(Tree $tree)
    {
        $rules = $this->visit($tree->getChildren()[0]);

        return new RuleCollection($rules);
    }

    private function walkRuleset(Tree $tree)
    {
        $rules = [];

        foreach ($tree->getChildren() as $child) {
            $rules[] = $this->visit($child);
        }

        $flattenedRules = [];
        array_walk_recursive($rules, function (Rule $rule) use (&$flattenedRules) {
            $flattenedRules[] = $rule;
        });

        return $flattenedRules;
    }

    private function walkRule(Tree $tree)
    {
        list($identifier, $separator, $ruleParts, $eol) = $tree->getChildren();

        return new Rule(
            $this->visit($identifier),
            $this->visit($ruleParts)
        );
    }

    private function walkRuleparts(Tree $tree)
    {
        $parts = [];

        foreach ($tree->getChildren() as $child) {
            $parts[] = $this->visit($child);
        }

        $flattenedParts = [];
        array_walk_recursive($parts, function (Entry $entry) use (&$flattenedParts) {
            $flattenedParts[] = $entry;
        });

        return $flattenedParts;
    }

    private function walkRulepart(Tree $tree)
    {
        return $this->visit($tree->getChildren()[0]);
    }

    private function walkReference(Tree $tree)
    {
        return new Reference($this->visit($tree->getChildren()[0]));
    }

    private function walkToken(Tree $tree)
    {
        $token = $this->visit($tree->getChildren()[0]);
        $token = substr($token, 1);

        return new TokenReference($token);
    }
}
