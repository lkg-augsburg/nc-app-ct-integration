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

  private function buildHeaders($token)
  {
    return [
      'Accept' => 'application/json',
      'Authorization' => 'Login ' . $token
    ];
  }

  private function _execGet(string $url, string $token, array $params = null)
  {
    if ($params != null) {
      $url = $url . "?" . implode("&", array_map(function ($k, $v) {
        if (is_array($v)) {
          return implode("&", array_map(fn($field) => $k . "[]=" . $field, $v));
        }
        return "$k=$v";
      }, array_keys($params), array_values($params)));
    }

    $response = $this->client->get($url, [
      'headers' => $this->buildHeaders($token),
    ]);

    return new JSONResponse(json_decode($response->getBody(), true));
  }

  public function fetchCsrfToken($url, $token)
  {
    $path = '/api/csrftoken';
    return $this->_execGet($url . $path, $token);
  }

  public function fetchWhoAmI($url, $token)
  {
    $path = '/api/whoami?only_allow_authenticated=true';
    return $this->_execGet($url . $path, $token);
  }

  public function fetchTags($url, $token)
  {
    $path = '/api/tags?type=persons';
    return $this->_execGet($url . $path, $token);
  }

  public function fetchGroupTypes($url, $token)
  {
    $path = '/api/group/grouptypes';
    return $this->_execGet($url . $path, $token);
  }

  public function fetchGroups($url, $token, $params)
  {
    $path = '/api/groups';
    return $this->_execGet($url . $path, $token, $params);
  }
}