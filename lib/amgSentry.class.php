<?php

/**
 * @author Jean Roussel <jroussel@amg-dev.fr>
 * @copyright AMG Développement | Groupe GPdis
 *
 */
class amgSentry extends Raven_Client {

	const DEBUG = 'debug';
    const INFO = 'info';
    const WARN = 'warning';
    const WARNING = 'warning';
    const ERROR = 'error';
    const FATAL = 'fatal';

	static protected $_instance = null;
	static public function getInstance(){
		if (null === self::$_instance) {
			if (!sfConfig::get('app_amg_sentry_dsn')) {
				throw new Exception('Please configure amgSentryPlugin in your app.yml (use model in "amgSentryPlugin/config/app.yml")');
			}
			self::$_instance = new amgSentry(sfConfig::get('app_amg_sentry_dsn'));
		}
		return self::$_instance;
	}

	static public function sendMessage($title, $description = '', $level = self::INFO){
		return self::getInstance()->captureMessage($title, array('description' => $description), $level);
	}

	static public function sendException($exception, $description = ''){
		return self::getInstance()->captureException($exception, $description);
	}

	public function capture($data, $stack){
		if (!empty($data['sentry.interfaces.Message']['params']['description'])) {
			$data['culprit'] = $data['message'];
			$data['message'] = $data['sentry.interfaces.Message']['params']['description'];
			unset($data['sentry.interfaces.Message']['params']['description']);
		}
		if (!empty($data['sentry.interfaces.Exception']['value'])) {
			$data['message'] = $data['culprit'];
			$data['culprit'] = $data['sentry.interfaces.Exception']['value'];
		}
		if (!sfConfig::get('app_amg_sentry_enabled', false)) {
			return true;
		}
		if (!isset($data['logger'])) {
			if (null !== self::$_logger) {
				$data['logger'] = self::$_logger;
			} elseif (sfConfig::get('app_amg_sentry_logger')) {
				$data['logger'] = sfConfig::get('app_amg_sentry_logger');
			} else {
				$data['logger'] = sfConfig::get('sf_app');
			}
		}
		return parent::capture($data, $stack);
	}

	static protected $_logger = null;
	static public function setLogger($logger){
		self::$_logger = $logger;
	}
	static public function resetLogger(){
		self::$_logger = null;
	}

}