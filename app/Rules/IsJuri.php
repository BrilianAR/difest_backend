<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class IsJuri implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Cek apakah user dengan ID yang diberikan ada dan punya role 'juri'
        $isJuri = User::where('id', $value)->where('role', 'juri')->exists();

        if (!$isJuri) {
            $fail('User harus memiliki peran sebagai juri.');
        }
    }
}
