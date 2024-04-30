<?php

namespace OCA\ChurchToolsIntegration\Listener;

use OCA\ChurchToolsIntegration\AppInfo\Application;
use OCP\Collaboration\Reference\RenderReferenceEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

class PexelsReferenceListener implements IEventListener
{
  public function handle(Event $event): void
  {
    if (!$event instanceof RenderReferenceEvent) {
      return;
    }

    Util::addScript(Application::APP_ID, Application::APP_ID . '-reference');
  }
}