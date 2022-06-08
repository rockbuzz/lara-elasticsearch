<?php

namespace Rockbuzz\LaraElasticSearch\Commands;

use ReflectionClass;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use Rockbuzz\LaraElasticSearch\Contracts\Searchable;

class IndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index {models}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all rows for model to Elasticsearch';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function handle()
    {
        $models = explode(',', $this->argument('models'));

        foreach ($models as $model) {
            if (file_exists(app_path("Models/$model.php"))) {
                $class = "\\App\\Models\\$model";
                $interface = Searchable::class;

                if ((new ReflectionClass($class))->implementsInterface($interface)) {
                    $this->info("Indexing all {$model}. This might take a while...");

                    $this->index("\\App\\Models\\$model");
            
                    $this->info("\nDone!");
                } else {
                    $this->warn("$class does not implement interface $interface");
                }
            } else {
                $this->warn("$model does not exists.");
            }
        }
    }

    protected function index(string $class)
    {
        $class::chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $this->client->index([
                    'index' => $row->getSearchIndex(),
                    'id' => $row->getSearchId(),
                    'body' => $row->getSearchBody(),
                ]);
    
                $this->output->write('.');
            }
        });
    }
}
