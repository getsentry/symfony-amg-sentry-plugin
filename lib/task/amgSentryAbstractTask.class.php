<?php

/*
 * This file is part of the symfony package.
 * (c) Nicolas Dubois <ndubois@amg-dev.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Abstract class for Sentry Plugin
 *
 * @package    amgSentryPlugin
 * @subpackage task
 * @author     Nicolas Dubois <ndubois@amg-dev.fr>
 */
abstract class amgSentryAbstractTask extends sfBaseTask
{

  const TASK_NAMESPACE = 'sentry';

  /**
   * @see sfTask
   */
  protected function preConfigure($name, $application = null, $env = 'dev')
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', $application),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', $env),
    ));

    $this->namespace = self::TASK_NAMESPACE;
    $this->name = $name;

    $this->createConfiguration($application, $env);
  }

  protected function addDescription($briefDescription, $detailedDescription = null) 
  {
    if (is_null($detailedDescription)) {
      $detailedDescription = $briefDescription;
    }

    $this->briefDescription = $briefDescription;
    $this->detailedDescription = $detailedDescription;
    }
}