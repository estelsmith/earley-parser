<?php

namespace ESJ\Earley\Recognizer\State;

use ESJ\Earley\Recognizer\State;

class Validator
{
    /**
     * @param State $state
     * @param string $startRuleName
     * @return bool
     */
    public function isValidState(State $state, $startRuleName)
    {
        $sets = $state->getSets();
        $endSet = $sets[$sets->count() - 1];

        foreach ($endSet->getItems() as $item) {
            $valid = true;

            $valid = $valid && ($item->isComplete());
            $valid = $valid && ($item->getInputPosition() === 0);
            $valid = $valid && ($item->getRule()->getName() === $startRuleName);

            if ($valid) {
                return true;
            }
        }

        return false;
    }
}
