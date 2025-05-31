<?php

namespace App\Card;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

/**
 * Evaluates a poker hand based on a set of predefined rules.
 */
class HandEvaluator
{
    /** @var RuleInterface[] */
    private array $rules;

    /**
     * @param RuleInterface[] $rules Array of rule objects implementing RuleInterface.
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Evaluates the hand and returns the name of the first matching rule.
     *
     * @param Hand $hand The hand to evaluate.
     * @return string The name of the matching hand rule or 'High Card'.
     */
    public function evaluate(Hand $hand): string
    {
        foreach ($this->rules as $rule) {
            if ($rule->matches($hand)) {
                return $rule->getName();
            }
        }

        return 'High Card';
    }
}
