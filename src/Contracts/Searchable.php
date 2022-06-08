<?php

namespace Rockbuzz\LaraElasticSearch\Contracts;

interface Searchable
{
    public function getSearchIndex();

    public function getSearchId();

    public function getSearchBody();
}
