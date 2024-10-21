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
    return $this->appConfig->setAppValueString("ctUrl", $value);
  }

  public function getCtUserToken()
  {
    return $this->appConfig->getAppValueString("ctUserToken");
  }

  public function setCtUserToken($value)
  {
    return $this->appConfig->setAppValueString("ctUserToken", $value);
  }

  public function getCtSyncGroups(bool $grouped = false)
  {
    $syncGroups = $this->appConfig->getAppValueArray("ctSyncGroups");

    if($grouped){
      return $syncGroups;
    }

    return array_merge(...array_values($syncGroups));
  }

  public function setCtSyncGroups(array $value)
  {
    return $this->appConfig->setAppValueArray("ctSyncGroups", $value);
  }


}