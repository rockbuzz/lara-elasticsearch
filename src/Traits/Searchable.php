<?php

namespace Rockbuzz\LaraElasticSearch\Traits;

use Illuminate\Support\Str;

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
}
