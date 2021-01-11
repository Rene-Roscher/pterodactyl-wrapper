<?php


namespace RexlManu\Pterodactyl\Sections;


use RexlManu\Pterodactyl\Wrapper\WrapperClient;

/**
 * Class ClientSection
 *
 * @package RexlManu\Pterodactyl\Sections
 *
 * @method object get( $endpoint, $query = [] )
 * @method object post( $endpoint, $data = [] )
 * @method object delete( $endpoint, $data = [] )
 * @method object put( $endpoint, $data = [] )
 * @method object patch( $endpoint, $data = [] )
 */
class ClientSection extends WrapperClient
{

  public function __construct($token)
  {
    parent::__construct($token, 'client');
  }

}