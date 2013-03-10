<?php

class amgSentryPluginConfiguration extends sfPluginConfiguration
{
	public function initialize()
	{
		if (!sfConfig::get('app_amg_sentry_enabled', false)) {
			return;
		}

		$this->dispatcher->connect('application.throw_exception', array('amgSentry', 'notify'));

		if (sfConfig::get('app_amg_sentry_report404', false)) {
			$this->dispatcher->connect('controller.page_not_found', array('amgSentry', 'notify404'));
		}

		amgSentryErrorNotifierErrorHandler::start();
	}
}
