<?php

namespace OCA\ChurchToolsIntegration\Service;

use OCP\AppFramework\Http\JSONResponse;
use OCP\Http\Client\IClientService;

class CtRestClient
{

  private $clientService;
  private $client;

  public function __construct(IClientService $clientService)
  {
    $this->clientService = $clientService;
    $this->client = $this->clientService->newClient();
  }

  public function fetchCsrfToken($url, $token)
  {
    $url = $url . '/api/csrftoken';
    $response = $this->client->get($url, [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Login ' . $token
      ],
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }

  public function fetchWhoAmI($url, $token)
  {
    $url = $url . '/api/whoami?only_allow_authenticated=true';
    $response = $this->client->get($url, [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Login ' . $token
      ],
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }

  public function fetchTags($url, $token)
  {
    $url = $url . '/api/tags?type=persons';
    $response = $this->client->get($url, [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Login ' . $token
      ],
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }

  public function fetchGroupTypes($url, $token)
  {
    $url = $url . '/api/group/grouptypes';
    $response = $this->client->get($url, [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Login ' . $token
      ],
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }
}