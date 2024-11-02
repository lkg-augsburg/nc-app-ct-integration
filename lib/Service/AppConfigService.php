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

  public function getGroupSync()
  {
    return $this->appConfig->getAppValueArray("groupSync");
  }

  public function setGroupSync(array $value)
  {
    return $this->appConfig->setAppValueArray("groupSync", $value);
  }

  public function getGroupTypeSync()
  {
    return $this->appConfig->getAppValueArray("groupTypeSync");
  }

  public function setGroupTypeSync(array $value)
  {
    return $this->appConfig->setAppValueArray("groupTypeSync", $value);
  }

  public function getGroupFolderSync()
  {
    return $this->appConfig->getAppValueArray("groupFolderSync");
  }

  public function setGroupFolderSync(array $value)
  {
    return $this->appConfig->setAppValueArray("groupFolderSync", $value);
  }

  public function getGroupTypeFolderSync()
  {
    return $this->appConfig->getAppValueArray("groupTypeFolderSync");
  }

  public function setGroupTypeFolderSync(array $value)
  {
    return $this->appConfig->setAppValueArray("groupTypeFolderSync", $value);
  }


}