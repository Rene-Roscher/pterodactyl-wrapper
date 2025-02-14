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

  public function request($method, $endpoint, $data, $json)
  {
    $response = Http::withToken($this->token)
      ->contentType('application/json')
      ->{$method}($this->buildUrl($endpoint), $data);
    $responseData = $response->{$json ? 'json' : 'body'}();
    if ($json) {
        if (is_null($responseData)) {
            $responseData = [];
        }
        $responseData[ 'status' ] = $response->status();
        return json_decode(json_encode(ConvertHelper::convert($responseData)));
    }
    return $responseData;
  }


  public function __call($name, $arguments)
  {
    if (count($arguments) < 0) {
      throw new Exception('Endpoint is required as first parameter');
    }
    if (count($arguments) == 1) {
      $arguments[ 1 ] = [];
    }
    if (count($arguments) < 3) {
      $arguments[ 2 ] = true;
    }
    return $this->request($name, $arguments[ 0 ], $arguments[ 1 ], $arguments[2]);
  }


}