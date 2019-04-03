<?php

/*
 * Translates a number to a short alphanumeric version
 *
 * @author Kevin van Zonneveld <kevin@transloadit.com> (https://github.com/kvz)
 * @author Simon Franz
 * @author Deadfish
 * @author SK83RJOSH
 * @author Alexey Samara <lion.samara@gmail.com> (https://wow-apps.pro)
 * @copyright 2008 Kevin van Zonneveld.
 */

namespace Kvz\YoutubeId;

use Kvz\YoutubeId\Exception\ConverterException;

class Converter
{
    const TRANSFORM_NONE = 0;
    const TRANSFORM_UPPERCASE = 1;
    const TRANSFORM_LOWERCASE = 2;

    /**
     * @param string      $input
     * @param int         $padUp
     * @param string|null $secureKey
     *
     * @return int
     */
    public static function toNumeric(string $input, int $padUp = 0, string $secureKey = null): int
    {
        $dictionary = self::generateDictionary();
        $dictionaryLength = strlen($dictionary);

        if (!empty($secureKey)) {
            $dictionary = self::secure($dictionary, $dictionaryLength, $secureKey);
        }

        return self::convertToNumber($input, $dictionary, $dictionaryLength, $padUp);
    }

    /**
     * @param int         $input
     * @param int         $padUp
     * @param string|null $secureKey
     * @param int         $transformType
     *
     * @return string
     */
    public static function toAlphanumeric(
        int $input,
        int $padUp = 0,
        string $secureKey = null,
        int $transformType = self::TRANSFORM_NONE
    ): string {
        $dictionary = self::generateDictionary();
        $dictionaryLength = strlen($dictionary);

        if (!empty($secureKey)) {
            $dictionary = self::secure($dictionary, $dictionaryLength, $secureKey);
        }

        return self::transform(
            self::convertToAlphanumeric((int) $input, $dictionary, $dictionaryLength, $padUp),
            $transformType
        );
    }

    /**
     * @return string
     */
    private static function generateDictionary(): string
    {
        return implode(range('a', 'z')) . implode(range(0, 9)) . implode(range('A', 'Z'));
    }

    /**
     * Although this function's purpose is to just make the ID short - and not so much secure,
     * with this patch by Simon Franz (http://blog.snaky.org/) you can optionally supply a password to make it harder
     * to calculate the corresponding numeric ID.
     *
     * Modernized by Alexey Samara (https://wow-apps.pro).
     *
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param string $secureKey
     *
     * @return string
     */
    private static function secure(string $dictionary, int $dictionaryLength, string $secureKey): string
    {
        $dictionaryArray = str_split($dictionary);
        $secureHash = strlen(hash('sha256', $secureKey)) < $dictionaryLength
            ? hash('sha512', $secureKey)
            : hash('sha256', $secureKey);
        $securedAlphabetArray = str_split(substr($secureHash, 0, $dictionaryLength));

        array_multisort(
            $securedAlphabetArray,
            SORT_DESC,
            $dictionaryArray
        );

        return implode($dictionaryArray);
    }

    /**
     * @param string $input
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param int    $padUp
     *
     * @return string
     */
    private static function convertToNumber(
        string $input,
        string $dictionary,
        int $dictionaryLength,
        int $padUp = 0
    ): string {
        $result = 0;
        $len = strlen($input) - 1;

        for ($t = $len; $t >= 0; --$t) {
            $bcp = bcpow($dictionaryLength, $len - $t);
            $result = $result + strpos($dictionary, substr($input, $t, 1)) * $bcp;
        }

        if (--$padUp > 0) {
            $result -= pow($dictionaryLength, $padUp);
        }

        return $result;
    }

    /**
     * @param int    $input
     * @param string $dictionary
     * @param int    $dictionaryLength
     * @param int    $padUp
     *
     * @return string
     */
    private static function convertToAlphanumeric(
        int $input,
        string $dictionary,
        int $dictionaryLength,
        int $padUp = 0
    ): string {
        if (--$padUp > 0) {
            $input += pow($dictionaryLength, $padUp);
        }

        $output = '';
        for ($t = (0 != $input ? floor(log($input, $dictionaryLength)) : 0); $t >= 0; --$t) {
            $bcp = bcpow($dictionaryLength, $t);
            $a = floor($input / $bcp) % $dictionaryLength;
            $output .= substr($dictionary, $a, 1);
            $input -= $a * $bcp;
        }

        return $output;
    }

    /**
     * @param string $input
     * @param int    $transformType
     *
     * @return string
     */
    private static function transform(string $input, int $transformType): string
    {
        switch ($transformType) {
            case self::TRANSFORM_NONE:
                return $input;
            case self::TRANSFORM_UPPERCASE:
                return strtoupper($input);
            case self::TRANSFORM_LOWERCASE:
                return strtolower($input);
            default:
                throw new ConverterException(ConverterException::E_TRANSFORM_TYPE);
        }
    }
}
