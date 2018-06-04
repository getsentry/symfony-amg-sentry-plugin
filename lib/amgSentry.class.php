<?php

/**
 * amgSentry allows you to send message and exception to Sentry.
 *
 * @author Jean Roussel <jroussel@amg-dev.fr>
 * @copyright AMG Développement | Groupe GPdis
 *
 */
class amgSentry extends Raven_Client
{
    protected static $_instance = null;
    protected static $_logger = null;

    /**
    * Retrieves the singleton instance of this class.
    *
    * @return amgSentry A amgSentry implementation instance.
    */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            if (!sfConfig::get('app_amg_sentry_dsn')) {
                throw new Exception('Please configure amgSentryPlugin in your app.yml (use model in "amgSentryPlugin/config/app.yml")');
            }

            $environment = sfProjectConfiguration::getActive()->isProduction() ? 'production' : 'development';
            $params      = [
                'release' => sfConfig::get('app_amg_sentry_release'),
                'tags'    => [
                    'environment' => $environment,
                    'instance'    => INSTANCE_BASE_URL,
                ],
            ];

            self::$_instance = new amgSentry(sfConfig::get('app_amg_sentry_dsn'), $params);
        }
        return self::$_instance;
    }

    /**
    * Send a message to Sentry.
    *
    * @param string $title Message title
    * @param string $description Message description
    * @param string $level Message level
    *
    * @return integer Sentry event ID
    */
    public static function sendMessage($title, $description = '', $level = self::INFO)
    {
        if (!sfConfig::get('app_amg_sentry_enabled', false)) {
            return true;
        }
        return self::getInstance()->captureMessage($title, ['description' => $description], $level);
    }

    /**
    * Send an exception to Sentry.
    *
    * @param Exception $exception Exception
    * @param string $description Exception description
    *
    * @return integer Sentry event ID
    */
    public static function sendException($exception, $description = '')
    {
        if (!sfConfig::get('app_amg_sentry_enabled', false)) {
            return true;
        }
        return self::getInstance()->captureException($exception, ['description' => $description]);
    }

    /**
     * Log a message to sentry
     */
    public function capture($data, $stack = null, $vars = null)
    {
        if (!sfConfig::get('app_amg_sentry_enabled', false)) {
            return true;
        }
        if (sfConfig::get('app_amg_sentry_release', null)) {
            $data['release'] = sfConfig::get('app_amg_sentry_release', null);
        }
        $data['culprit'] = null;
        if (!empty($data['sentry.interfaces.Message']['params']['description'])) {
            $data['culprit'] = $data['message'];
            $data['message'] = $data['sentry.interfaces.Message']['params']['description'];
            unset($data['sentry.interfaces.Message']['params']['description']);
        }
        if (!empty($data['sentry.interfaces.Exception']['value'])) {
            $data['message'] = $data['culprit'];
            $data['culprit'] = $data['sentry.interfaces.Exception']['value'];
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
        return parent::capture($data, $stack, $vars);
    }

    /**
    * Set Sentry logger.
    *
    * @param string $logger Logger
    */
    public static function setLogger($logger)
    {
        self::$_logger = $logger;
    }
    /**
    * Reset Sentry logger.
    */
    public static function resetLogger()
    {
        self::$_logger = null;
    }

    public static function notify(sfEvent $event)
    {
        $e = $event->getSubject();
        if ($e instanceof Exception) {
            return self::notifyException($e);
        }
        return;
    }

    public static function notify404(sfEvent $event)
    {
        $e = $event->getSubject();
        if ($e instanceof Exception) {
            return self::notifyException($e);
        } else {
            $uri = sfContext::getInstance()->getRequest()->getUri();
            return self::notifyException(new sfError404Exception("Page not found [404][uri: $uri]"));
        }
    }

    public static function notifyException($exception)
    {
        // it's not an error.
        if ($exception instanceof sfStopException) {
            return;
        }
        self::sendException($exception);
    }
}
