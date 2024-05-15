<?php

namespace OCA\ChurchToolsIntegration\Service;

use OCP\AppFramework\Http\JSONResponse;
use OCP\Http\Client\IClientService;
use OCP\Http\Client\IClient;

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
    $client = $this->clientService->newClient();
    $url = $url . '/api/csrftoken';
    $headers = [
      'Accept' => 'application/json',
      'Authorization' => 'Login ' . $token
    ];
    // $response = $client->get($url, [
    //   'headers' => [
    //     'Accept' => 'application/json',
    //     'Authorization' => 'Login ' . $token
    //   ],
    // ]);

    // return new JSONResponse(json_decode($response->getBody(), true));
    return new JSONResponse([
      'url' => $url,
      'headers' => $headers,
    ]);
  }

  public function fetchWhoAmI($url, $token)
  {
    // $client = $this->clientService->newClient();
    $url = $url . '/api/whoami?only_allow_authenticated=true';
    $response = $this->client->get($url, [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Login ' . $token
      ],
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }
}