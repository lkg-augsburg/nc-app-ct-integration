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
    
    return new JSONResponse([
      'status' => 'ok',
      'data' => [
        'userMail' => $respData['email'],
        'userId' => $respData['id'],
        'userName' => $respData['cmsUserId'],
        'orgName' => $respInfoData['siteName'],
        'orgShortName' => $respInfoData['shortName'],
      ],
      'message' => 'Credentials okay ' . $url,
    ]);
  }

  public function fetchWhoAmI($url, $token)
  {
    return $this->ctRestClient->fetchWhoAmI($url, $token);
  }

  public function fetchCsrfToken($url, $token)
  {
    return $this->ctRestClient->fetchCsrfToken($url, $token);
  }

  public function fetchTags($url, $token)
  {
    return $this->ctRestClient->fetchTags($url, $token);
  }

  public function fetchGroupTypes($url, $token)
  {
    return $this->ctRestClient->fetchGroupTypes($url, $token);
  }

  public function fetchGroupTypeGroups($id)
  {
    return $this->ctRestClient->fetchGroupTypeGroups($id);
  }

  public function saveConfiguration()
  {
    $results = [];
    $params = $this->request->getParams();

    $url = $params['ctUrl'] ?? null;
    $token = $params['ctToken'] ?? null;
    $ctSyncGroups = $params['ctSyncGroups'] ?? null;

    if (isset($url) && isset($token)) {
      $results = array_replace($results, $this->saveCtCredentials($url, $token));
    }
    if (isset($ctSyncGroups)) {
      $results = array_replace($results, $this->saveCtGroupsConfig($ctSyncGroups));
    }

    return new JSONResponse([
      'status' => 'ok',
      'data' => $results,
      'message' => 'Configuration saved'
    ]);
  }

  private function saveCtCredentials($url, $token)
  {
    return [
      ["key" => "ctUrl", "value" => json_encode($this->appConfigService->setCtUrl($url))],
      ["key" => "ctUserToken", "value" => json_encode($this->appConfigService->setCtUserToken($token))],
    ];
  }

  private function saveCtGroupsConfig(array $ctSyncGroups)
  {
    $results = [
      ["key" => "ctSyncGroups", "value" => json_encode($this->appConfigService->setCtSyncGroups(($ctSyncGroups)))],
    ];

    return $results;
  }

  public function fetchGroups()
  {
    return $this->ctRestClient->fetchSyncGroups();
  }

  public function fetchUsersForGroup($groupId)
  {
    $url = $this->appConfig->getAppValueString("ctUrl");
    $token = $this->appConfig->getAppValueString("ctUserToken");
  }
}