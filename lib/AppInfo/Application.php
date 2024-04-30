<?php

namespace OCA\ChurchToolsIntegration\AppInfo;

use OCA\ChurchToolsIntegration\Listener\PexelsReferenceListener;
use OCA\ChurchToolsIntegration\Reference\PhotoReferenceProvider;
use OCA\ChurchToolsIntegration\Search\PexelsSearchPhotosProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\Collaboration\Reference\RenderReferenceEvent;

class Application extends App implements IBootstrap
{
	public const APP_ID = 'churchtoolsintegration';

	public function __construct(array $urlParams = [])
	{
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void
	{
		$context->registerSearchProvider(PexelsSearchPhotosProvider::class);
		$context->registerReferenceProvider(PhotoReferenceProvider::class);
		$context->registerEventListener(RenderReferenceEvent::class, PexelsReferenceListener::class);
	}

	public function boot(IBootContext $context): void
	{
	}
}