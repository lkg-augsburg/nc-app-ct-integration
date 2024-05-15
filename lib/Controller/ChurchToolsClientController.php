<?php

namespace OCA\ChurchToolsIntegration\Controller;

use OCA\ChurchToolsIntegration\Service\CtRestClient;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\Http\Client\IClientService;
use OCP\Http\Client\IClient;

class ChurchToolsClientController extends Controller
{
  private $clientService;
  private $ctRestClient;

  public function __construct($AppName, IRequest $request, IClientService $clientService, CtRestClient $ctRestClient)
  {
    parent::__construct($AppName, $request);
    $this->clientService = $clientService;
    $this->ctRestClient = $ctRestClient;
  }

  public function test()
  {
    return new JSONResponse(
      array(
        'status' => 'ok',
        'data' => null,
        'message' => 'Test succeeded!'
      )
    );
  }

  public function fetchCsrfToken($url, $token)
  {
    return $this->ctRestClient->fetchCsrfToken($url, $token);
  }

  public function fetchWhoAmI($url, $token)
  {
    return $this->ctRestClient->fetchWhoAmI($url, $token);
  }
}