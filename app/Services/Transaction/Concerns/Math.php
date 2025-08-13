<?php

namespace App\Services\Transaction\Concerns;


use RuntimeException;

trait Math
{
    /**
     * @param float $num1
     * @param float $num2
     * @return bool
     */
    private function shouldNegative(float $num1, float $num2): bool
    {
        return bccomp($num1, $num2, 2) < 0;
    }

    /**
     * @param float $num1
     * @param float $num2
     * @return string
     */
    private function subtract(float $num1, float $num2): string
    {
        return bcsub($num1, $num2, 2);
    }

    /**
     * @param float $num1
     * @param float $num2
     * @return string
     */
    private function sum(float $num1, float $num2): string
    {
        return bcadd($num1, $num2, 2);
    }
}
