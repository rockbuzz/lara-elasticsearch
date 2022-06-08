<?php

namespace Rockbuzz\LaraElasticSearch\Traits;

use Elasticsearch\Client;
use Illuminate\Support\Str;
use Rockbuzz\LaraElasticSearch\Observers\SearchObserver;

trait Searchable
{
    public function getSearchIndex()
    {
        return Str::slug(
            implode('.', [
                config('app.name'),
                config('app.env'),
                $this->getTable(),
            ]),
            '.'
        );
    }

    public function getSearchId()
    {
        return $this->getKey();
    }

    public function getSearchBody()
    {
        return $this->toArray();
    }

    public function searchIndex(Client $client)
    {
        $client->index([
            'index' => $this->getSearchIndex(),
            'id' => $this->getSearchId(),
            'body' => $this->getSearchBody()
        ]);
    }

    public function searchDelete(Client $client)
    {
        $client->delete([
            'index' => $this->getSearchIndex(),
            'id' => $this->getSearchId()
        ]);
    }

    public static function bootSearchable()
    {
        if (config('services.search.enabled')) {
            static::observe(SearchObserver::class);
        }
    }
}
