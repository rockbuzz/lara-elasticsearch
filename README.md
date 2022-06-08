# Lara Elastic Search

Lara Elastic Search

<p><img src="https://github.com/rockbuzz/lara-elasticsearch/workflows/Main/badge.svg"/></p>

## Requirements

PHP >=7.3

## Install

```bash
$ composer require rockbuzz/lara-elasticsearch
```

## Usage

```php
//config/services.php
return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'search' => [
        'enabled' => env('ELASTICSEARCH_ENABLED', false),
        'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', '127.0.0.1:9200')),
    ],
    //....
];
```

```php
//config/logging.php
return [

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */
    'channels' => [
        'elasticsearch' => [
            'driver'         => 'monolog',
            'level'          => 'debug',
            'handler'        => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter'      => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX'),
                'type'  => '_doc',
            ],
            'handler_with'   => [
                'client' => \Elasticsearch\ClientBuilder::create()
                    ->setHosts([env('ELASTICSEARCH_HOSTS')])
                    ->build(),
            ],
        ],
        //...
    ],
    //...
];
```

## License

The Lara Cw Api is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).