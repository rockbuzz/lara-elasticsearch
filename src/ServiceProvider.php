<?php

namespace Rockbuzz\LaraElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Filesystem\Filesystem;
use Rockbuzz\LaraElasticSearch\Commands\IndexCommand;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{

    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/elasticsearch.php' => config_path('elasticsearch.php')
            ], 'config');
        }

        $this->commands([
            IndexCommand::class
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/elasticsearch.php', 'elasticsearch');

        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('elasticsearch.hosts'))
                ->build();
        });
    }
}
