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
    // $this->clientService = $clientService;
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

  public function saveCtCredentials($url, $token)
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
      $this->appConfig->setAppValueString($field["key"], $field["value"], sensitive: true);
    }

    return new JSONResponse($results);
  }
}