# symfony's amgSentryPlugin

The `amgSentryPlugin` is a Symfony 1.4 plugin for the Sentry PHP client.

This plugin is based on the Sentry PHP client library [sentry-php](https://github.com/getsentry/sentry-php).

## Requirements

* PHP ≥ 5.2
* symfony ≥ 1.4
* Sentry instance

## Installation

In the `plugins` folder of your symfony project.

### The Composer way

Add the require to your composer.json. It's oddly named but like this Composer's symfony1 installer camelcases it correctly. Composer will install it into your project's plugins directory automatically, and add the requirements.

```json
{
    "config": {
        "vendor-dir": "lib/vendor"
    },
    "require": {
        "amg-dev/amg-sentry-plugin": "dev-master"
    }
}
```

Of course, don't forget to add Composer's autoloader to your ProjectConfiguration:

```php
// config/ProjectConfiguration.class.php

require __DIR__ .'/../lib/vendor/autoload.php';

require_once dirname(__FILE__) .'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
    public function setup()
    {
        $this->enablePlugins(array(
            'amgSentryPlugin',
            ...
        ));

        // mandatory because of the Composer vendor directory naming scheme
        sfConfig::set('sf_sentry_path', sfConfig::get('sf_lib_dir') . '/vendor/sentry/sentry');
    }
}
```

### Via git clone

```
$ git clone git@github.com:amg-dev/symfony-amg-sentry-plugin.git plugins/amgSentryPlugin
$ cd plugins/amgSentryPlugin
$ git submodule update --init
```

### Via git submodule

```
$ git submodule add github.com:amg-dev/symfony-amg-sentry-plugin.git plugins/amgSentryPlugin
$ git submodule update --init --recursive
```

### Via zip archive

[Download](https://github.com/amg-dev/symfony-amg-sentry-plugin/archive/master.zip) and extract zip archive.

## Configuration

In your symfony project folder.

### Activate the plugin in `config/ProjectConfiguration.class.php`

```php
class ProjectConfiguration extends sfProjectConfiguration
{
	public function setup()
	{
		$this->enablePlugins(array(
			'sfDoctrinePlugin',
			'amgSentryPlugin',
			'...'
		));
	}
}
```

### Configure the plugin in `config/app.yml` (project and/or application level)

```yaml
prod:
  amg_sentry:
    enabled: true
    dsn: 'http://public:secret@sentry.example.com:9000/[PROJECT_ID]'
    logger: 'custom-logger-name'
    reportPHPErrors: true
    reportPHPWarnings: false
    report404: false
```

 * `dsn` - If it is possible for sites with high traffic use UDP protocol.
 * `reportPHPErrors` - If true, it sends a notification when there is a exception.
 * `reportPHPWarnings` - If true, it sends a notification when there is a warning.
 * `report404` - If true, it sends a notification when a user lands on a page 404. It should be used only for the duration of the study, the risk is that web crawlers will produce a lot of notifications.

### (Optional) Enable the helper in `config/settings.yml` (application level)

```yaml
.settings:
  standard_helpers: [default, Sentry, ...]
```

### (Optional) Configure the symfony logger in `config/factories.yml` (project and/or application level)

```yaml
prod:
  logger:
    param:
      loggers:
        amg_sentry_logger:
          class: amgSentryLogger
          param:
            level: warning
```

Additional docs about logging:

* [The factories.yml Configuration File (official doc)](http://symfony.com/legacy/doc/reference/1_4/en/05-Factories)
* [Enable logging in the production environment](http://symfony-blog.driebit.nl/2010/11/enable-logging-in-the-production-environment/)

### Clear the cache

```
$ symfony cc
```

## Usage

### amgSentry

```php
// send a message with no description and information level (by default)
amgSentry::sendMessage('Message title');

// send a debug message
amgSentry::sendMessage('Debug message title', 'Debug message description', amgSentry::DEBUG);

// send a warning message
amgSentry::sendMessage('Warning message title', 'Warning message description', amgSentry::WARNING);

// send an error message
amgSentry::sendMessage('Error message title', 'Error message description', amgSentry::ERROR);

// send an exception
amgSentry::sendException(new Exception('Exception message'), 'Exception description');

// set logger
amgSentry::setLogger('new-logger');

// reset logger
amgSentry::resetLogger();
```

### SentryHelper

```php
// send a message with no description and information level (by default)
sentry_send_message('Message title');

// send a debug message
sentry_send_message('Debug message title', 'Debug message description', amgSentry::DEBUG);

// send a warning message
sentry_send_message('Warning message title', 'Warning message description', amgSentry::WARNING);

// send an error message
sentry_send_message('Error message title', 'Error message description', amgSentry::ERROR);

// send an exception
sentry_send_exception(new Exception('Exception message'), 'Exception description');

// set logger
sentry_set_logger('new-logger');

// reset logger
sentry_reset_logger();
```

### sfLogger

```php
// log a debug message
sfContext::getInstance()->getLogger()->debug('Debug message');

// log an error message
sfContext::getInstance()->getLogger()->err('Error message');
```

## Contributors

* Nicolas Dubois <nicolas.c.dubois@gmail.com>
* Jean Roussel <contact@jean-roussel.fr>
* Arkadiusz Tułodziecki <atulodzi@gmail.com>

### vendor/raven-php

The Sentry PHP client is maintained by the Sentry Team.

http://github.com/getsentry/sentry-php
