<?php
namespace OCA\ChurchToolsIntegration\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IConfig;
use OCP\Settings\ISettings;

use OCA\ChurchToolsIntegration\AppInfo\Application;

class Admin implements ISettings
{

  private IConfig $config;
  private IInitialState $initialStateService;
  private ?string $userId;

  public function __construct(
    IConfig $config,
    IInitialState $initialStateService,
    ?string $userId
  ) {
    $this->config = $config;
    $this->initialStateService = $initialStateService;
    $this->userId = $userId;
  }

  /**
   * @return TemplateResponse
   */
  public function getForm(): TemplateResponse
  {
    // $ctUser = $this->config->getAppValue(Application::APP_ID, 'churchToolsUser');
    // $ctPw = $this->config->getAppValue(Application::APP_ID, 'churchToolsPassword');

    $state = [
      'ctUrl' => '',
      'ctUser' => '',
      'ctPw' => '',
    ];
    // $this->initialStateService->provideInitialState('admin-config', $state);
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