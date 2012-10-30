<?php

/*
 * This file is part of the symfony package.
 * (c) Nicolas Dubois <ndubois@amg-dev.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Get Sentry configuration
 *
 * @package    amgSentryPlugin
 * @subpackage task
 * @author     Nicolas Dubois <ndubois@amg-dev.fr>
 */
class amgSentryGetConfigurationTask extends amgSentryAbstractTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->preConfigure('get-configuration');
    $this->addDescription('Gets Sentry configuration');
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('Sentry', sfConfig::get('app_amg_sentry_enabled') ? 'enabled' : 'disabled');
    $this->logSection('DSN', sfConfig::get('app_amg_sentry_dsn', 'not configured'));
    $this->logSection('Logger', sfConfig::get('app_amg_sentry_logger', 'default'));
    $this->logSection('Client', sprintf('raven-php: version %s', Raven_Client::VERSION));
  }
}