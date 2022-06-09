# Laravel Elastic Search

Laravel Elastic Search integration

<p><img src="https://github.com/rockbuzz/lara-elasticsearch/workflows/Main/badge.svg"/></p>

## Requirements

PHP >=7.3

## Install

```bash
$ composer require rockbuzz/lara-elasticsearch
```

## Usage

```env
ELASTICSEARCH_ENABLED=true
ELASTICSEARCH_HOSTS="__elasticsearch__:9200"
ELASTICSEARCH_LOGS_INDEX="app.local.logs"
```

```php
//config/services.php
<?php
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraElasticSearch\Contracts\Searchable;
use Rockbuzz\LaraElasticSearch\Traits\Searchable as TraitsSearchable;

class Article extends Model implements Searchable
{
    use TraitsSearchable;

    //Optionally you can override the getSearchBody method, by default $this->toArray() is used
    public function getSearchBody()
    {
        return [
            //
        ];
    }
}
```
To index the data referring to a model, run:
```bash
$ php artisan search:index Article
```

Logging

```php
//config/logging.php
<?php
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
                'index' => env('ELASTICSEARCH_LOGS_INDEX'),
                'type'  => '_doc',
            ],
            'handler_with'   => [
                'client' => \Elasticsearch\ClientBuilder::create()
                    ->setHosts(explode(',', env('ELASTICSEARCH_HOSTS', '127.0.0.1:9200')))
                    ->build(),
            ],
        ],
        //...
    ],
    //...
];
```

## License

The Lara Elastic Search is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).