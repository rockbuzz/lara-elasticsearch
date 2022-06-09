<?php

namespace Rockbuzz\LaraElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Rockbuzz\LaraElasticSearch\Commands\IndexCommand;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{

    public function boot()
    {
        $this->commands([
            IndexCommand::class
        ]);
    }

    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts', []))
                ->build();
        });
    }
}
