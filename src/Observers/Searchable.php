<?php

namespace Rockbuzz\LaraElasticSearch\Observers;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function saved($model)
    {
        $model->searchIndex($this->client);
    }

    public function deleted($model)
    {
        $model->searchDelete($this->client);
    }
}
