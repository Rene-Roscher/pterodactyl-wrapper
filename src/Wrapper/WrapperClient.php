<?php

namespace RexlManu\Pterodactyl\Wrapper;

use Exception;
use Illuminate\Support\Facades\Http;
use RexlManu\Pterodactyl\Helper\ConvertHelper;

class WrapperClient
{

  private $token;
  private $section;

  /**
   * WrapperClient constructor.
   *
   * @param $token
   * @param $section
   */
  public function __construct($token, $section)
  {
    $this->token = $token;
    $this->section = $section;
    if (! str_ends_with(config('pterodactyl.auth.host'), '/') && ! str_starts_with($this->section, '/')) {
      $this->section = '/' . $this->section;
    }
  }

  private function buildUrl($endpoint)
  {
    if (! str_starts_with($endpoint, '/')) {
      $endpoint = '/' . $endpoint;
    }
    return config('pterodactyl.auth.host') . '/api' . $this->section . $endpoint;
  }

  public function request($method, $endpoint, $data, $json = true)
  {
    $response = Http::withToken($this->token)
      ->contentType('application/json')
      ->{$method}($this->buildUrl($endpoint), $data);
    $responseJson = $response->{$json ? 'json' : 'body'}();
    if (is_null($responseJson)) {
      $responseJson = [];
    }
    $responseJson[ 'status' ] = $response->status();
    return json_decode(json_encode(ConvertHelper::convert($responseJson)));
  }


  public function __call($name, $arguments)
  {
    if (count($arguments) < 0) {
      throw new Exception('Endpoint is required as first parameter');
    }
    if (count($arguments) == 1) {
      $arguments[ 1 ] = [];
    }
    return $this->request($name, $arguments[ 0 ], $arguments[ 1 ]);
  }


}