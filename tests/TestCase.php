<?php

namespace Tests;

use Rockbuzz\LaraElasticSearch\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/../database/migrations'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/migrations'),
        ]);

        $this->withFactories(__DIR__.'/factories');
    }


    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }


    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
