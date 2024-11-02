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
}