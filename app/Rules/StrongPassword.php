<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if value is string
        if (!is_string($value)) {
            $fail('Password must be a string.');
            return;
        }

        // Check 1: Minimum 8 characters
        $length = strlen($value);
        if ($length < 8) {
            $fail("Password must be at least 8 characters (you entered {$length}).");
            return;
        }

        // Check 2: At least 3 uppercase letters
        $uppercaseCount = preg_match_all('/[A-Z]/', $value);
        if ($uppercaseCount < 3) {
            $fail("Password must contain at least 3 uppercase letters (found {$uppercaseCount}).");
            return;
        }

        // Check 3: At least one number
        $hasNumber = preg_match('/\d/', $value);
        if (!$hasNumber) {
            $fail('Password must contain at least one number (0-9).');
            return;
        }

        // Check 4: At least one symbol
        $hasSymbol = preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $value);
        if (!$hasSymbol) {
            $fail('Password must contain at least one symbol (!@#$%^&*()-_=+{};:,<.>).');
            return;
        }
    }
}