<?php

return [
    'hosts' => env('ELASTICSEARCH_HOSTS', '127.0.0.1:9200'),
    'logs_index' => env('ELASTIC_LOGS_INDEX', 'logs'),
];
