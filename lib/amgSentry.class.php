<?php

class amgSentry extends Raven_Client {

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

	static public function sendMessage($message, $params=array(), $level=self::INFO, $stack=false){
		return self::getInstance()->captureMessage($message, $params, $level, $stack);
	}

	static public function sendException($exception, $culprit=null, $logger=null){
		return self::getInstance()->captureException($exception, $culprit, $logger);
	}

	public function capture($data, $stack){
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