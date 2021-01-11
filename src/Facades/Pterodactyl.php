<?php

namespace RexlManu\Pterodactyl\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use RexlManu\Pterodactyl\Sections\ClientSection;

/**
 * Class Pterodactyl
 *
 * @package RexlManu\Pterodactyl\Facades
 * @method static object get( $endpoint, $query = [] )
 * @method static object post( $endpoint, $data = [] )
 * @method static object delete( $endpoint, $data = [] )
 * @method static object put( $endpoint, $data = [] )
 * @method static object patch( $endpoint, $data = [] )
 * @method static ClientSection client( $token )
 */
class Pterodactyl extends Facade
{

  protected static function getFacadeAccessor()
  {
    return 'pterodactyl.application';
  }

}