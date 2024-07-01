<?php

namespace OCA\ChurchToolsIntegration\Service;

use Exception;
use OCA\ChurchToolsIntegration\Models\CtGroupMember;
use OCA\ChurchToolsIntegration\Models\CtUser;
use OCP\AppFramework\Http\JSONResponse;
use OCP\Http\Client\IClientService;

use Psr\Log\LoggerInterface;

class CtRestClient
{

  private $clientService;
  private $client;
  private $appConfigService;
  private $logger;

  public function __construct(
    IClientService $clientService,
    AppConfigService $appConfigService,
    LoggerInterface $logger,
  ) {
    $this->clientService = $clientService;
    $this->client = $this->clientService->newClient();
    $this->appConfigService = $appConfigService;
    $this->logger = $logger;
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


    try {
      $response = $this->client->get($url, [
        'headers' => $this->buildHeaders($token),
      ]);
      return new JSONResponse(json_decode($response->getBody(), true));
    } catch (Exception $e) {
      $this->logger->error($e);
      return new JSONResponse([json_encode($e)]);
    }
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

  public function fetchGroupTypeGroups($id){
    $path = '/api/groups';
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();
    $params = [
      "limit" => 200,
      "page" => 1,
      "include[]" => "tags",
      "group_type_ids" => [$id]
    ];

    $results = [];

    while (true) {
      $resp = $this->fetchGroups($url, $token, $params);
      $data = $resp->getData()["data"];
      $meta = $resp->getData()["meta"];
      $pagination = $meta["pagination"];

      $results = array_merge($results, $data);
      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $results;
  }

  /**
   * Summary of fetchGroupMembers
   * @param string $gid
   * @return CtGroupMember[]
   */
  public function fetchGroupMembers(string $gid)
  {
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();
    $params = [
      "limit" => 200,
      "page" => 1,
      "include[]" => "tags",
    ];

    $path = "/api/groups/$gid/members";

    $results = [];

    while (true) {
      $resp = $this->_execGet($url . $path, $token, $params);
      $data = $resp->getData()["data"];
      $meta = $resp->getData()["meta"];
      $pagination = $meta["pagination"];

      $results = array_merge($results, array_map(function ($member) {
        try {
          return CtGroupMember::fromJson($member);
        } catch (Exception $e) {
          $this->logger->error($e->getMessage());
        }
      }, $data));
      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $results;

  }

  public function fetchSyncGroups(
  ) {
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();
    $groupSyncTypes = $this->appConfigService->getCtSyncGroups();
    $params = [
      "limit" => 200,
      "page" => 1,
      "include[]" => "tags",
      "ids" => $groupSyncTypes
    ];

    $results = [];

    while (true) {
      $resp = $this->fetchGroups($url, $token, $params);
      $data = $resp->getData()["data"];
      $meta = $resp->getData()["meta"];
      $pagination = $meta["pagination"];

      $results = array_merge($results, $data);
      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $results;
  }

  /**
   * Summary of fetchUsers
   * @param int[]|string[] $userIds
   * @return CtUser[]
   */
  public function fetchUsers($userIds = [])
  {
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();
    $params = [
      "limit" => 200,
      "page" => 1,
    ];
    if (!empty($userIds)) {
      $params["ids"] = $userIds;
    }

    $path = "/api/persons";

    $results = [];

    while (true) {
      $resp = $this->_execGet($url . $path, $token, $params);
      $data = $resp->getData()["data"];
      $meta = $resp->getData()["meta"];
      $pagination = $meta["pagination"];

      $results = array_merge($results, array_map(function ($member) {
        try {
          return CtUser::fromJson($member);
        } catch (Exception $e) {
          $this->logger->error($e->getMessage());
        }
      }, $data));
      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $results;
  }
}