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

namespace Kvz\YoutubeId\Exception;

use InvalidArgumentException;

class ConverterException extends InvalidArgumentException
{
    const E_TRANSFORM_TYPE = 'Unknown transform type. Allowed types are: '
        . 'Converter::TRANSFORM_NONE, Converter::TRANSFORM_UPPERCASE, Converter::TRANSFORM_LOWERCASE';
}
