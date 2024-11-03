<?php
namespace OCA\ChurchToolsIntegration\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Settings\ISettings;

use OCA\ChurchToolsIntegration\AppInfo\Application;
use OCP\AppFramework\Services\IAppConfig;

class Admin implements ISettings
{

  private IAppConfig $appConfig;
  private IInitialState $initialStateService;

  public function __construct(
    IAppConfig $appConfig,
    IInitialState $initialStateService,
  ) {
    $this->appConfig = $appConfig;
    $this->initialStateService = $initialStateService;
  }

  /**
   * @return TemplateResponse
   */
  public function getForm(): TemplateResponse
  {
    $this->initialStateService->provideInitialState('configuration', [
      'ctUrl' => $this->appConfig->getAppValueString('ctUrl'),
      'ctToken' => $this->appConfig->getAppValueString('ctUserToken'),
      'groupSync' => $this->appConfig->getAppValueArray('groupSync'),
      'groupTypeSync' => $this->appConfig->getAppValueArray('groupTypeSync'),
      'groupFolderSync' => $this->appConfig->getAppValueArray('groupFolderSync'),
      'groupTypeFolderSync' => $this->appConfig->getAppValueArray('groupTypeFolderSync'),
      'deactivatedGroupTypes' => $this->appConfig->getAppValueArray('deactivatedGroupTypes'),
    ]);
    return new TemplateResponse(Application::APP_ID, 'adminSettings');
  }

  public function getSection(): string
  {
    return 'churchtools';
  }

  public function getPriority(): int
  {
    return 10;
  }
}