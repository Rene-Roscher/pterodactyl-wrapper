<?php

namespace RexlManu\Pterodactyl\Wrapper;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RexlManu\Pterodactyl\Helper\ConvertHelper;

class WrapperClient
{

  private $token;
  private $section;
  private $client;

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
    if (! Str::endsWith(config('pterodactyl.auth.host'), '/') && ! Str::endsWith($this->section, '/')) {
      $this->section = '/' . $this->section;
    }
    $this->client = new Client();
  }

  private function buildUrl($endpoint)
  {
    if (! Str::start($endpoint, '/')) {
      $endpoint = '/' . $endpoint;
    }
    return config('pterodactyl.auth.host') . '/api' . $this->section . $endpoint;
  }

  public function request($method, $endpoint, $data)
  {
    $response = $this->client->request($method, $this->buildUrl($endpoint), [
      RequestOptions::BODY => $data,
      RequestOptions::HEADERS => [
        'Content-Type' => 'application/json'
      ]
    ]);
    $responseJson = $response->getBody();
    if (is_null($responseJson)) {
      $responseJson = [];
    }
    $responseJson[ 'status' ] = $response->getStatusCode();
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