<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O CPF informado e invalido.');

            return;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;

            for ($c = 0; $c < $t; $c++) {
                $d += ((int) $cpf[$c]) * (($t + 1) - $c);
            }

            $digito = ((10 * $d) % 11) % 10;

            if ((int) $cpf[$t] !== $digito) {
                $fail('O CPF informado e invalido.');

                return;
            }
        }
    }
}
