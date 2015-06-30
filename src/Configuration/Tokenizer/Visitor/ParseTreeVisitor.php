<?php

namespace ESJ\Earley\Configuration\Tokenizer\Visitor;

use ESJ\Earley\Parser\Leaf;
use ESJ\Earley\Parser\Tree;
use ESJ\Earley\Tokenizer;
use ESJ\Earley\Tokenizer\Definition;

class ParseTreeVisitor
{
    /**
     * @param Tree $tree
     * @return Tokenizer
     */
    public function dispatch(Tree $tree)
    {
        $definitions = $this->visit($tree);

        return new Tokenizer($definitions);
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
        return $this->visit($tree->getChildren()[0]);
    }

    private function walkRuleset(Tree $tree)
    {
        $rules = [];

        foreach ($tree->getChildren() as $child) {
            $rules[] = $this->visit($child);
        }

        $flattenedRules = [];
        array_walk_recursive($rules, function (Definition $definition) use (&$flattenedRules) {
            $flattenedRules[] = $definition;
        });

        return $flattenedRules;
    }

    private function walkRule(Tree $tree)
    {
        list($identifier, $separator, $pattern) = $tree->getChildren();

        return new Definition(
            $this->visit($identifier),
            preg_replace('/\/(.+)\//', '$1', $this->visit($pattern))
        );
    }
}
