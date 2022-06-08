<?php

namespace Rockbuzz\LaraElasticSearch\Contracts;

use Elasticsearch\Client;

interface Searchable
{
    public function getSearchIndex();

    public function getSearchId();

    public function getSearchBody();

    public function searchIndex(Client $client);

    public function searchDelete(Client $client);

    public static function bootSearchable();
}
