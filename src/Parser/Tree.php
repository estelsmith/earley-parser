<?php

namespace ESJ\Earley\Parser;

use ESJ\Earley\Recognizer\Rule\Rule;

class Tree implements Node
{
    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var Node[]
     */
    private $children = [];

    /**
     * @param Rule $rule
     */
    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param Node $child
     */
    public function addChild(Node $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
