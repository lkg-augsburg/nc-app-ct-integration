<?php

namespace OCA\ChurchToolsIntegration\Controller;

use OCA\ChurchToolsIntegration\Service\CtRestClient;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Services\IAppConfig;

class ChurchToolsClientController extends Controller
{
  private $appConfig;
  private $ctRestClient;

  public function __construct(
    $AppName,
    IRequest $request,
    IAppConfig $appConfig,
    CtRestClient $ctRestClient,
  ) {
    parent::__construct($AppName, $request);
    $this->ctRestClient = $ctRestClient;
    $this->appConfig = $appConfig;
  }

  public function fetchCsrfToken($url, $token)
  {
    return $this->ctRestClient->fetchCsrfToken($url, $token);
  }

  public function fetchWhoAmI($url, $token)
  {
    return $this->ctRestClient->fetchWhoAmI($url, $token);
  }

  public function fetchTags($url, $token)
  {
    return $this->ctRestClient->fetchTags($url, $token);
  }

  public function fetchGroupTypes($url, $token)
  {
    return $this->ctRestClient->fetchGroupTypes($url, $token);
  }

  public function saveConfiguration()
  {
    $results = [];
    $params = $this->request->getParams();

    $url = $params['ctUrl'] ?? null;
    $token = $params['ctToken'] ?? null;
    $groupSyncTag = $params['ctGroupsSyncTag'] ?? null;
    $groupSyncTypes = $params['ctGroupSyncTypes'] ?? null;
    if (isset($url) && isset($token)) {
      $results = array_replace($results, $this->saveCtCredentials($url, $token));
    }
    if (isset($groupSyncTag) && isset($groupSyncTypes)) {
      $results = array_replace($results, $this->saveCtGroupsConfig($groupSyncTag, $groupSyncTypes));
    }

    return new JSONResponse([
      'status' => 'ok',
      'data' => $results,
      'message' => 'Configuration saved'
    ]);
  }

  private function saveCtCredentials($url, $token)
  {
    $whoami = $this->fetchWhoAmI($url, $token)->getData()['data'];
    $mail = $whoami['email'];

    $fields = [
      ["key" => "ctUrl", "value" => $url],
      ["key" => "ctUserMail", "value" => $mail],
      ["key" => "ctUserToken", "value" => $token],
    ];

    $results = [];

    foreach ($fields as $field) {
      $fieldKey = $field["key"];
      $fieldValue = $field["value"];
      $results[$field["key"]] = json_encode($this->appConfig->setAppValueString($fieldKey, $fieldValue, sensitive: true));
    }

    return $results;
  }

  private function saveCtGroupsConfig($groupSyncTag, $groupSyncTypes)
  {
    $results = [];

    $results["ctGroupSyncTag"] = json_encode($this->appConfig->setAppValueString("ctGroupSyncTag", json_encode($groupSyncTag)));
    $results["ctGroupSyncTypes"] = json_encode($this->appConfig->setAppValueArray("ctGroupSyncTypes", $groupSyncTypes));

    return $results;
  }

  public function fetchGroups()
  {
    $url = $this->appConfig->getAppValueString("ctUrl");
    $token = $this->appConfig->getAppValueString("ctUserToken");
    $groupSyncTag = json_decode($this->appConfig->getAppValueString("ctGroupSyncTag"));
    $groupSyncTypes = $this->appConfig->getAppValueArray("ctGroupSyncTypes");
    $groupSyncValue = $groupSyncTag->value;

    $params = [
      "limit" => 10,
      "page" => 1,
      "include[]" => "tags",
      "group_type_ids" => $groupSyncTypes
    ];

    $results = [];

    while (true) {
      $resp = $this->ctRestClient->fetchGroups($url, $token, $params);
      $data = $resp->getData()["data"];
      $meta = $resp->getData()["meta"];
      $pagination = $meta["pagination"];

      $results = array_merge(
        $results,
        array_filter(
          $data,
          function ($group) use ($groupSyncValue) {
            foreach ($group["tags"] as $tag) {
              if ($tag["id"] == $groupSyncValue) {
                return true;
              }
            }
            return false;
          }
        )
      );
      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $results;
  }
}