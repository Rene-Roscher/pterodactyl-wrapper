<?php


namespace RexlManu\Pterodactyl\Sections;


use RexlManu\Pterodactyl\Wrapper\WrapperClient;

class ApplicationSection extends WrapperClient
{

  public function __construct()
  {
    parent::__construct(config('pterodactyl.auth.application'), 'application');
  }

  public function client($token)
  {
    return new ClientSection($token);
  }

}