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
        return $client->index([
            'index' => $this->getSearchIndex(),
            'id' => $this->getSearchId(),
            'body' => $this->getSearchBody()
        ]);
    }

    public function searchGet(Client $client)
    {
        return $client->get([
            'index' => $this->getSearchIndex(),
            'id' => $this->getSearchId()
        ]);
    }

    public function searchGetSource(Client $client)
    {
        return $client->getSource([
            'index' => $this->getSearchIndex(),
            'id' => $this->getSearchId()
        ]);
    }

    public function searchDelete(Client $client)
    {
        return $client->delete([
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
