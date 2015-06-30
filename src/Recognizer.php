<?php

namespace ESJ\Earley;

use ESJ\Earley\Recognizer\Item;
use ESJ\Earley\Recognizer\Rule\Entry\Reference;
use ESJ\Earley\Recognizer\Rule\Entry\TokenReference;
use ESJ\Earley\Recognizer\Rule\Rule;
use ESJ\Earley\Recognizer\Rule\RuleCollection;
use ESJ\Earley\Recognizer\Set;
use ESJ\Earley\Recognizer\State;
use ESJ\Earley\Tokenizer\Token;

class Recognizer
{
    /**
     * @var RuleCollection
     */
    private $rules;

    /**
     * @var string
     */
    private $startRuleName;

    /**
     * @param RuleCollection $rules
     * @param string $startRuleName
     */
    public function __construct(RuleCollection $rules, $startRuleName)
    {
        $this->rules = $rules;
        $this->startRuleName = $startRuleName;
    }

    /**
     * @param Token[] $input
     * @return State
     * @throws \Exception
     */
    public function recognize($input)
    {
        $state = new State($this->startRuleName);
        $state->addSet($this->createInitialSet());

        foreach ($state->getSets() as $setIndex => $set) {
            foreach ($set->getItems() as $itemIndex => $item) {
                $entry = $item->getEntryInRule();

                if (is_null($entry)) {
                    $this->complete($state, $set, $item);
                } else {
                    $class = get_class($entry);

                    switch ($class) {
                        case Reference::class:
                            $this->predict($state, $set, $entry);
                            break;
                        case TokenReference::class:
                            $this->scan($state, $input, $item, $entry);
                            break;
                        default:
                            throw new \Exception(sprintf('Unknown rule "%s"', $class));
                            break;
                    }
                }
            }
        }

        if (!$state->isValid()) {
            throw new \Exception('Could not successfully parse input');
        }

        return $state;
    }

    /**
     * @param State $state
     * @param Set $set
     * @param Item $item
     */
    private function complete(State $state, Set $set, Item $item)
    {
        $ruleName = $item->getRule()->getName();

        /** @var Item[] $searchItems */
        $searchItems = $state->getSets()[$item->getInputPosition()]->getItems()->getArrayCopy();

        foreach ($searchItems as $searchItem) {
            $searchEntry = $searchItem->getEntryInRule();

            if ($searchEntry instanceof Reference && $searchEntry->getRuleName() === $ruleName) {
                $set->addItem(new Item(
                    $searchItem->getRule(),
                    $searchItem->getEntryPosition() + 1,
                    $searchItem->getInputPosition()
                ));
            }
        }
    }

    /**
     * @param State $state
     * @param Set $set
     * @param Reference $reference
     */
    private function predict(State $state, Set $set, Reference $reference)
    {
        $referencedRules = $this->rules->filter(function (Rule $rule) use ($reference) {
            return $rule->getName() === $reference->getRuleName();
        });
        $inputPosition = $state->getSets()->key();

        foreach ($referencedRules as $rule) {
            $set->addItem(new Item($rule, 0, $inputPosition));
        }
    }

    /**
     * @param State $state
     * @param Token[] $input
     * @param Item $item
     * @param TokenReference $tokenReference
     */
    private function scan(State $state, $input, Item $item, TokenReference $tokenReference)
    {
        $inputPosition = $state->getSets()->key();

        if (!array_key_exists($inputPosition, $input)) {
            return;
        }

        $token = $input[$inputPosition];

        if ($tokenReference->matchesToken($token)) {
            $nextInputPosition = $inputPosition + 1;

            if (!array_key_exists($nextInputPosition, $state->getSets())) {
                $state->addSet(new Set());
            }

            $nextSet = $state->getSets()[$nextInputPosition];
            $nextSet->addItem(new Item(
                $item->getRule(),
                $item->getEntryPosition() + 1,
                $item->getInputPosition()
            ));
        }
    }

    /**
     * @return Set
     */
    private function createInitialSet()
    {
        $startRuleName = $this->startRuleName;
        $initialSet = new Set();

        $rules = $this->rules->filter(function (Rule $rule) use ($startRuleName) {
            return $rule->getName() === $startRuleName;
        });

        foreach ($rules as $rule) {
            $initialSet->addItem(new Item($rule, 0, 0));
        }

        return $initialSet;
    }
}
