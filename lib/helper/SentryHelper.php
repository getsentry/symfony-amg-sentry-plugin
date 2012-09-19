<?php

/**
* Send a message to Sentry.
*
* @param string $title Message title
* @param string $description Message description
* @param string $level Message level
*
* @return integer Sentry event ID 
*/
function sentry_send_message($title, $description = '', $level = self::INFO){
	return amgSentry::getInstance()->sendMessage($title, $description, $level);
}

/**
* Send an exception to Sentry.
*
* @param Exception $exception Exception
* @param string $description Exception description
*
* @return integer Sentry event ID 
*/
function sentry_send_exception($exception, $description = ''){
	return amgSentry::getInstance()->sendException($exception, $description);
}

/**
* Set Sentry logger.
*
* @param string $logger Logger
*/
function sentry_set_logger($logger){
	amgSentry::getInstance()->setLogger($logger);
}

/**
* Reset Sentry logger.
*/
function sentry_reset_logger(){
	amgSentry::getInstance()->resetLogger();
}
