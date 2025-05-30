<?php
namespace App\Card\Rule;

use App\Card\Hand;

interface RuleInterface
{
    public function matches(Hand $hand): bool;

    public function getName(): string;
}