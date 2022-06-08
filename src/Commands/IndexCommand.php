<?php

namespace Rockbuzz\LaraElasticSearch\Commands;

use ReflectionClass;
use Elasticsearch\Client;
use App\Contracts\Searchable;
use Illuminate\Console\Command;

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

                if ((new ReflectionClass($class))->implementsInterface($interface)){

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
        $class::chunkById(100, function($votes){
            foreach ($votes as $vote){
                $this->client->index([
                    'index' => $vote->getSearchIndex(),
                    'id' => $vote->getSearchId(),
                    'body' => $vote->getSearchBody(),
                ]);
    
                $this->output->write('.');
            }
        });
    }
}
