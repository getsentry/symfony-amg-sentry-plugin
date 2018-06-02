<?php

/**
 * amgSentryLogger allows you send symfony logs to Sentry.
 *
 * @author Jean Roussel <jroussel@amg-dev.fr>
 * @copyright AMG Développement | Groupe GPdis
 *
 */

// defeat the autoload race condition when __FILE__ is loaded before amgSentry.class.php
if (!class_exists('amgSentry')) {
    require_once 'amgSentry.class.php';
}

class amgSentryLogger extends sfLogger {

	/**
	* Logs a message.
	*
	* @param string $message   Message
	* @param string $priority  Message priority
	*
	* @see sfLogger::doLog
	*/
	protected function doLog($message, $priority){
		amgSentry::sendMessage($message, '', $this->_getSentryLevelFromLoggerPriority($priority));
	}

	/**
	* Map symfony logger error priority to Sentry level.
	*
	* @param integer $priority Logger priority
	*
	* @return string Sentry level
	*/
	protected function _getSentryLevelFromLoggerPriority($priority){
		switch ($priority) {
			case self::EMERG:
			case self::ALERT:
			case self::CRIT:
				return amgSentry::FATAL;
			case self::ERR:
				return amgSentry::ERROR;
			case self::WARNING:
				return amgSentry::WARNING;
			case self::NOTICE:
			case self::INFO:
				return amgSentry::INFO;
			case self::DEBUG:
				return amgSentry::DEBUG;
		}

		throw new Exception(sprintf('Unknown priority "%s" in Sentry.', $priority));
	}

}
