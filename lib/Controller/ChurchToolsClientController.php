<?php

namespace OCA\ChurchToolsIntegration\Controller;

use OCA\ChurchToolsIntegration\Service\AppConfigService;
use OCA\ChurchToolsIntegration\Service\CtRestClient;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Services\IAppConfig;

class ChurchToolsClientController extends Controller
{
  private $appConfig;
  private $ctRestClient;
  private $appConfigService;

  public function __construct(
    $AppName,
    IRequest $request,
    IAppConfig $appConfig,
    CtRestClient $ctRestClient,
    AppConfigService $appConfigService,
  ) {
    parent::__construct($AppName, $request);
    $this->ctRestClient = $ctRestClient;
    $this->appConfig = $appConfig;
    $this->appConfigService = $appConfigService;
  }

  private function _buildResponse($status = '', $message = '', $data = null){
    return new JSONResponse([
      'status' => $status,
      'data' => $data,
      'message' => $message
    ]);
  }

  public function authenticate($url, $token){
    $resp = $this->ctRestClient->fetchWhoAmI($url, $token)->getData();
    if($resp['status'] != 'ok'){
      return $resp;
    }
    $respData = $resp['data']['data'];
    
    $respInfo = $this->ctRestClient->fetchInfo($url, $token)->getData();
    if($respInfo['status'] != 'ok'){
      return $respInfo;
    }
    $respInfoData = $respInfo['data'];

    return $this->_buildResponse(
      status: 'ok',
      message: 'Credentials okay ' . $url,
      data: [
        'userMail' => $respData['email'],
        'userId' => $respData['id'],
        'userName' => $respData['cmsUserId'],
        'orgName' => $respInfoData['siteName'],
        'orgShortName' => $respInfoData['shortName'],
      ]
    );
  }

  public function fetchWhoAmI($url, $token)
  {
    return $this->ctRestClient->fetchWhoAmI($url, $token);
  }

  public function fetchGroupTypes()
  {
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();
    $types = $this->ctRestClient->fetchGroupTypes($url, $token)->getData()['data']['data'];

    $results = [];

    foreach ($types as $type) {
      $results[] = [
        'id' => $type['id'],
        'name' => $type['name'],
        'description' => $type['description'],
      ];
    }

    return $this->_buildResponse(
      status: 'ok',
      data: $results,
    );
  }

  public function fetchAllGroups(){
    $url = $this->appConfigService->getCtUrl();
    $token = $this->appConfigService->getCtUserToken();

    $results = [];

    $params = [
      "limit" => 200,
      "page" => 1,
      //"include[]" => "tags",
    ];

    while (true) {
      $resp = $this->ctRestClient->fetchGroups($url, $token, $params)->getData()['data'];
      $data = $resp["data"];
      $meta = $resp["meta"];
      $pagination = $meta["pagination"];

      foreach ($data as $group) {
        $results[] = [
          'id' => $group['id'],
          'name' => $group['name'],
          'type' => $group['information']['groupTypeId'],
        ];
      }

      $params["page"] = $params["page"] + 1;

      if ($pagination["current"] >= $pagination["lastPage"]) {
        break;
      }
    }

    return $this->_buildResponse(
      status: 'ok',
      data: $results,
    );
  }

  // public function fetchGroupsGroupedByType(){
  //   $url = $this->appConfigService->getCtUrl();
  //   $token = $this->appConfigService->getCtUserToken();

  //   $types = $this->ctRestClient->fetchGroupTypes($url, $token)->getData()['data']['data'];

  //   $results = [
  //     'groupTypes' => [],
  //     'groups' => [],
  //   ];

  //   foreach ($types as $type) {
  //     $results['groupTypes'][$type['id']] = [
  //       'id' => $type['id'],
  //       'name' => $type['name'],
  //       'description' => $type['description'],
  //       'groups' => []
  //     ];
  //   }

  //   $params = [
  //     "limit" => 200,
  //     "page" => 1,
  //     //"include[]" => "tags",
  //   ];

  //   while (true) {
  //     $resp = $this->ctRestClient->fetchGroups($url, $token, $params)->getData()['data'];
  //     $data = $resp["data"];
  //     $meta = $resp["meta"];
  //     $pagination = $meta["pagination"];

  //     foreach ($data as $group) {
  //       $groupId = $group['id'];
  //       $groupTypeId = $group['information']['groupTypeId'];
  //       $element = [
  //         'id' => $groupId,
  //         'name' => $group['name'],
  //         'note' => $group['information']['note'],
  //         'type' => $groupTypeId,
  //       ];
  //       $results['groups'][] = $element;
  //       $results['groupTypes']['groups'][] = $groupId;
  //     }

  //     $params["page"] = $params["page"] + 1;

  //     if ($pagination["current"] >= $pagination["lastPage"]) {
  //       break;
  //     }
  //   }

  //   return $this->_buildResponse(
  //     status: 'ok',
  //     data: $results,
  //   );
  // }


  // public function fetchCsrfToken($url, $token)
  // {
  //   return $this->ctRestClient->fetchCsrfToken($url, $token);
  // }

  // public function fetchTags($url, $token)
  // {
  //   return $this->ctRestClient->fetchTags($url, $token);
  // }

  // public function fetchGroupTypeGroups($id)
  // {
  //   return $this->ctRestClient->fetchGroupTypeGroups($id);
  // }

  // public function saveConfiguration()
  // {
  //   $results = [];
  //   $params = $this->request->getParams();

  //   $url = $params['ctUrl'] ?? null;
  //   $token = $params['ctToken'] ?? null;
  //   $ctSyncGroups = $params['ctSyncGroups'] ?? null;

  //   if (isset($url) && isset($token)) {
  //     $results = array_replace($results, $this->saveCtCredentials($url, $token));
  //   }
  //   if (isset($ctSyncGroups)) {
  //     $results = array_replace($results, $this->saveCtGroupsConfig($ctSyncGroups));
  //   }

  //   return new JSONResponse([
  //     'status' => 'ok',
  //     'data' => $results,
  //     'message' => 'Configuration saved'
  //   ]);
  // }

  // private function saveCtCredentials($url, $token)
  // {
  //   return [
  //     ["key" => "ctUrl", "value" => json_encode($this->appConfigService->setCtUrl($url))],
  //     ["key" => "ctUserToken", "value" => json_encode($this->appConfigService->setCtUserToken($token))],
  //   ];
  // }

  // private function saveCtGroupsConfig(array $ctSyncGroups)
  // {
  //   $results = [
  //     ["key" => "ctSyncGroups", "value" => json_encode($this->appConfigService->setCtSyncGroups(($ctSyncGroups)))],
  //   ];

  //   return $results;
  // }

  // public function fetchGroups()
  // {
  //   return $this->ctRestClient->fetchSyncGroups();
  // }

  // public function fetchUsersForGroup($groupId)
  // {
  //   $url = $this->appConfig->getAppValueString("ctUrl");
  //   $token = $this->appConfig->getAppValueString("ctUserToken");
  // }
}