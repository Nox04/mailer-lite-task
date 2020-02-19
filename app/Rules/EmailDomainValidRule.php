<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailDomainValidRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        [, $domain] = explode("@", $value);

        return checkdnsrr($domain, "MX");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This email address does not exist. Please use an existent email.';
    }
}
