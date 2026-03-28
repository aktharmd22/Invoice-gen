<?php

namespace App\Helpers;

class NumberToWords
{
    private static array $ones = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
        'seventeen', 'eighteen', 'nineteen',
    ];

    private static array $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety',
    ];

    public static function convert(int $number): string
    {
        if ($number === 0) return 'zero rupees';
        if ($number < 0)  return 'minus ' . self::convert(-$number);

        $words = '';

        if ($number >= 10000000) {
            $words .= self::convert((int)($number / 10000000)) . ' crore ';
            $number  %= 10000000;
        }
        if ($number >= 100000) {
            $words .= self::convert((int)($number / 100000)) . ' lakh ';
            $number  %= 100000;
        }
        if ($number >= 1000) {
            $words .= self::convert((int)($number / 1000)) . ' thousand ';
            $number  %= 1000;
        }
        if ($number >= 100) {
            $words .= self::$ones[(int)($number / 100)] . ' hundred ';
            $number  %= 100;
        }
        if ($number >= 20) {
            $words .= self::$tens[(int)($number / 10)] . ' ';
            $number  %= 10;
        }
        if ($number > 0) {
            $words .= self::$ones[$number] . ' ';
        }

        return trim($words) . ' rupees';
    }
}
