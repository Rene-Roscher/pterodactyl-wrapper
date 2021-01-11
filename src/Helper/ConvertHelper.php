<?php

namespace RexlManu\Pterodactyl\Helper;

use Illuminate\Support\Arr;

class ConvertHelper
{

  public static function convert($responseJson)
  {
    return self::convertEveryElement(self::convertElement($responseJson));
  }

  private static function convertEveryElement($json)
  {
    foreach ($json as $key => $value) {
      if (is_array($value)) {
        $json[ $key ] = self::convertElement($value);
      }
    }
    return $json;
  }

  private static function convertElement($json)
  {
    if (array_key_exists('object', $json)) {
      if (array_key_exists('attributes', $json)) {
        $json[ $json[ 'object' ] ] = $json[ 'attributes' ];
        return Arr::except($json, [ 'object', 'attributes' ]);
      }
      if (array_key_exists('data', $json)) {
        return $json[ 'data' ];
      }
    }
    return $json;
  }
}