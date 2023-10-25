<?php

namespace Devertix\LaravelApiGenerator\Generators;

use Illuminate\Support\Str;

class PostmanExportGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return '';
    }

    protected function getDestinationFileName()
    {
        return 'api.postman_collection.json';
    }

    protected function extendReplaceData()
    {
    }

    public function make()
    {
        $jsonData = [
            'info' => [
                '_postman_id' => str_replace("urn:uuid:", "", Str::uuid()->getUrn()),
                'name' => env('APP_NAME'),
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'item' => [],
        ];
        if (is_file($this->destinationPath)) {
            $jsonData = json_decode(file_get_contents($this->destinationPath), true);
        }
        $jsonData['item'][] = [
            'name' => $this->modelName,
            'item' => [
                $this->makeListingExport(),
                $this->makeShowExport(),
                $this->makeCreateExport(),
                $this->makeUpdateExport(),
                $this->makeDeleteExport(),
            ],
        ];
        file_put_contents($this->destinationPath, json_encode($jsonData, JSON_PRETTY_PRINT));
        return $this->destinationPath;
    }

    private function makeListingExport()
    {
        return [
            'name' => 'Listing',
            'request' => [
                'method' => 'GET',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                    ],
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => '',
                ],
                'url' => [
                    'raw' => '{{hostname}}/api/' . \Str::snake($this->modelName),
                    'host' => [
                        '{{hostname}}',
                    ],
                    'path' => [
                        'api',
                        \Str::snake($this->modelName),
                    ],
                ],
            ],
            'response' => [],
        ];
    }

    private function makeShowExport()
    {
        return [
            'name' => 'Show',
            'request' => [
                'method' => 'GET',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                    ],
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => '',
                ],
                'url' => [
                    'raw' => '{{hostname}}/api/' . \Str::snake($this->modelName) . '/1',
                    'host' => [
                        '{{hostname}}',
                    ],
                    'path' => [
                        'api',
                        \Str::snake($this->modelName),
                        '1',
                    ],
                ],
            ],
            'response' => [],
        ];
    }

    private function makeDeleteExport()
    {
        return [
            'name' => 'Delete',
            'request' => [
                'method' => 'DELETE',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                    ],
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => '',
                ],
                'url' => [
                    'raw' => '{{hostname}}/api/' . \Str::snake($this->modelName) . '/1',
                    'host' => [
                        '{{hostname}}',
                    ],
                    'path' => [
                        'api',
                        \Str::snake($this->modelName),
                        '1',
                    ],
                ],
            ],
            'response' => [],
        ];
    }

    private function makeCreateExport()
    {
        $entity = [
            'data' => [
                'type' => \Str::snake($this->modelName),
                'attributes' => [],
            ],
        ];
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'boolean':
                case 'integer':
                    $entity['data']['attributes'][$fieldData['name']] = 1;
                    break;
                case 'string':
                    $entity['data']['attributes'][$fieldData['name']] = 'sample string';
                    break;
                case 'text':
                    $entity['data']['attributes'][$fieldData['name']] = 'sample text';
                    break;
                case 'datetime':
                    $entity['data']['attributes'][$fieldData['name']] = '2018-01-01 00:00:00';
                    break;
                default:
                    throw $this->invalidFieldTypeException($fieldData['type']);
                    break;
            }
        }
        return [
            'name' => 'Create',
            'request' => [
                'method' => 'POST',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                    ],
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => json_encode($entity, JSON_PRETTY_PRINT),
                ],
                'url' => [
                    'raw' => '{{hostname}}/api/' . \Str::snake($this->modelName),
                    'host' => [
                        '{{hostname}}',
                    ],
                    'path' => [
                        'api',
                        \Str::snake($this->modelName),
                    ],
                ],
            ],
            'response' => [],
        ];
    }

    private function makeUpdateExport()
    {
        $entity = [
            'data' => [
                'type' => \Str::snake($this->modelName),
                'id' => 1,
                'attributes' => [],
            ],
        ];
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'boolean':
                case 'integer':
                    $entity['data']['attributes'][$fieldData['name']] = 2;
                    break;
                case 'string':
                    $entity['data']['attributes'][$fieldData['name']] = 'new sample string';
                    break;
                case 'text':
                    $entity['data']['attributes'][$fieldData['name']] = 'new sample text';
                    break;
                case 'datetime':
                    $entity['data']['attributes'][$fieldData['name']] = '2018-01-02 00:00:00';
                    break;
                default:
                    throw $this->invalidFieldTypeException($fieldData['type']);
                    break;
            }
        }
        return [
            'name' => 'Update',
            'request' => [
                'method' => 'PATCH',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                    ],
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => json_encode($entity, JSON_PRETTY_PRINT),
                ],
                'url' => [
                    'raw' => '{{hostname}}/api/' . \Str::snake($this->modelName) . '/1',
                    'host' => [
                        '{{hostname}}',
                    ],
                    'path' => [
                        'api',
                        \Str::snake($this->modelName),
                        '1',
                    ],
                ],
            ],
            'response' => [],
        ];
    }
}
