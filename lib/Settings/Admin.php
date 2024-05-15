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
  private ?string $userId;

  public function __construct(
    IAppConfig $appConfig,
    IInitialState $initialStateService,
    ?string $userId
  ) {
    $this->appConfig = $appConfig;
    $this->initialStateService = $initialStateService;
    $this->userId = $userId;
  }

  /**
   * @return TemplateResponse
   */
  public function getForm(): TemplateResponse
  {
    $ctUrl = $this->appConfig->getAppValueString('ctUrl');
    $ctToken = $this->appConfig->getAppValueString('ctUserToken');

    $state = [
      'ctUrl' => $ctUrl,
      'ctToken' => $ctToken,
    ];
    $this->initialStateService->provideInitialState('ct-connection', $state);
    return new TemplateResponse(Application::APP_ID, 'adminSettings');
  }

  public function getSection(): string
  {
    return 'ct-integration';
  }

  public function getPriority(): int
  {
    return 10;
  }
}