<?php

namespace OCA\ChurchToolsIntegration\Service;

use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Services\IAppConfig;
use OCP\Http\Client\IClientService;

class AppConfigService
{

  private $appConfig;

  public function __construct(
    IAppConfig $appConfig,
  ) {
    $this->appConfig = $appConfig;
  }


  public function getCtUrl()
  {
    return $this->appConfig->getAppValueString("ctUrl");
  }

  public function setCtUrl($value)
  {
    $this->appConfig->setAppValueString("ctUrl", $value);
  }


  public function getCtUserToken()
  {
    return $this->appConfig->getAppValueString("ctUserToken");
  }

  public function setCtUserToken($value)
  {
    $this->appConfig->setAppValueString("ctUserToken", $value);
  }


  public function getCtUserMail()
  {
    return $this->appConfig->getAppValueString("ctUserMail");
  }

  public function setCtUserMail($value)
  {
    $this->appConfig->setAppValueString("ctUserMail", $value);
  }


  public function getCtGroupSyncTag()
  {
    return json_decode($this->appConfig->getAppValueString("ctGroupSyncTag"));
  }

  public function setCtGroupSyncTag($value)
  {
    $this->appConfig->setAppValueString("ctGroupSyncTag", json_encode($value));
  }


  public function getCtGroupSyncTypes()
  {
    return $this->appConfig->getAppValueArray("ctGroupSyncTypes");
  }

  public function setCtGroupSyncTypes(array $value)
  {
    $this->appConfig->setAppValueArray("ctGroupSyncTypes", $value);
  }


}