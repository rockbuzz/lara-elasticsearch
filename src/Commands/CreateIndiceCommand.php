<?php

namespace Rockbuzz\LaraElasticSearch\Commands;

use Elasticsearch\Client;
use Illuminate\Console\Command;

class CreateIndiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:create-indice {configFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create indice on Elasticsearch';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function handle()
    {
        $configFile = $this->argument('configFile');

        if (!file_exists($configFile)) {
            $this->error("File $configFile don\'t exist.");
            return;
        }

        $data = require $configFile;
        
        $this->client->indices()->create($data);

        $indice = $data['index'];

        $this->info("Indice $indice created successfully.");
    }
}
