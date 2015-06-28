<?php

namespace ESJ\Earley\Recognizer\Rule;

use ESJ\Earley\ToString;

class RuleCollection implements \IteratorAggregate, ToString
{
    /**
     * @var Rule[]
     */
    private $rules = [];

    /**
     * @param Rule[] $rules
     */
    public function __construct(array $rules)
    {
        $this->addRules($rules);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->rules);
    }

    public function __toString()
    {
        $rules = array_map(function (Rule $rule) {
            return $rule->__toString();
        }, $this->rules);

        return implode("\n", $rules) . "\n";
    }

    /**
     * @param array $rules
     */
    private function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * @param Rule $rule
     */
    private function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
    }
}
