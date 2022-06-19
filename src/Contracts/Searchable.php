<?php

namespace Rockbuzz\LaraElasticSearch\Contracts;

use Elasticsearch\Client;

interface Searchable
{
    public function getSearchIndex();

    public function getSearchId();

    public function getSearchBody();

    public function searchIndex(Client $client);

    public function searchGet(Client $client);

    public function searchGetSource(Client $client);

    public function searchDelete(Client $client);
}
