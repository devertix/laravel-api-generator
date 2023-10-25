<?php

namespace Devertix\LaravelApiGenerator\Console;

use Devertix\LaravelApiGenerator\Generators\Config\ConfigStore;
use Devertix\LaravelApiGenerator\Generators\ControllerGenerator;
use Devertix\LaravelApiGenerator\Generators\Exceptions\InvalidFieldTypeException;
use Devertix\LaravelApiGenerator\Generators\FactoryGenerator;
use Devertix\LaravelApiGenerator\Generators\MigrationGenerator;
use Devertix\LaravelApiGenerator\Generators\ModelGenerator;
use Devertix\LaravelApiGenerator\Generators\PostmanExportGenerator;
use Devertix\LaravelApiGenerator\Generators\PostRequestGenerator;
use Devertix\LaravelApiGenerator\Generators\PutRequestGenerator;
use Devertix\LaravelApiGenerator\Generators\RepositoryGenerator;
use Devertix\LaravelApiGenerator\Generators\RequestBaseGenerator;
use Devertix\LaravelApiGenerator\Generators\TestGenerator;
use Devertix\LaravelApiGenerator\Generators\TestOrderGenerator;
use Devertix\LaravelApiGenerator\Generators\ResourceGenerator;
use Faker\Generator;
use Illuminate\Console\Command;

class MakeApiResourceCommand extends Command
{
    protected $signature = 'make:apiresource {modelName?}';

    protected $description = 'Create a new api resource pack';

    private $modelConfig = [];

    private $fields = [];

    private $fieldTypes = [
        'string',
        'integer',
        'text',
        'datetime',
        'boolean',
        'akarmi',
    ];

    /**
     * @var Generator
     */
    protected $faker;

    public function handle(ConfigStore $config, Generator $faker)
    {
        $this->faker = $faker;
        $stubDirectory = __DIR__ . '/../../stubs';
        $config->modelName = $this->argument('modelName');
        if (empty($config->modelName)) {
            $config->modelName = $this->ask("Give a model name");
            if (empty($config->modelName)) {
                $this->error('No model name given.');
                return;
            }
        }
        $config->timestamps = $this->choice("Have this model timestamps?", ['yes', 'no'], 'yes') == 'yes' ? true : false;
        do {
            $fieldName = $this->ask("Give a field name");
            if (!empty($fieldName)) {
                $fieldType = $this->anticipate("What type is it", $this->fieldTypes);
                $config->fields[] = [
                    'name' => $fieldName,
                    'type' => $fieldType,
                ];
            }
        } while (!empty($fieldName));
        foreach ($this->getGenerators($config) as $className => $destination) {
            try {
                $destinationPath = (new $className($config, $stubDirectory, $destination, $this->faker))->make();
                $this->line('Generated file: ' . $destinationPath);
            }
            catch (InvalidFieldTypeException $exception) {
                $this->error($exception->getMessage());
            }
        }
        $this->addResourceRoute($config->modelName);
    }

    private function addResourceRoute($modelName)
    {
        $file = fopen(app_path() . '/../routes/api.php', 'a+');
        fwrite($file, "Route::apiresource('" . \Str::snake($modelName) .
                      "', \\App\\Http\\Controllers\\Api\\" . $modelName .
                      "Controller::class);\n");
        fclose($file);
        $this->line('Modified file: routes/api.php');
    }

    /**
     * @param $modelName
     * @return array
     */
    private function getGenerators(ConfigStore $config): array
    {
        $generators = [
            ModelGenerator::class =>
                app_path() . '/Models/',
            MigrationGenerator::class =>
                app_path() . '/../database/migrations/',
            FactoryGenerator::class =>
                app_path() . '/../database/factories/',
            RepositoryGenerator::class =>
                app_path() . '/Repositories/',
            ResourceGenerator::class =>
                app_path() . '/Http/Resources/',
            RequestBaseGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $config->modelName . '/',
            PostRequestGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $config->modelName . '/',
            PutRequestGenerator::class =>
                app_path() . '/Http/Requests/Api/' . $config->modelName . '/',
            ControllerGenerator::class =>
                app_path() . '/Http/Controllers/Api/',
            TestGenerator::class =>
                app_path() . '/../tests/Feature/Api/' . $config->modelName . '/',
            TestOrderGenerator::class =>
                app_path() . '/../tests/Feature/Api/' . $config->modelName . '/',
            PostmanExportGenerator::class =>
                app_path() . '/../tests/postman/',
        ];
        return $generators;
    }
}
