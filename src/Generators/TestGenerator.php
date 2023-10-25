<?php

namespace Devertix\LaravelApiGenerator\Generators;

use Carbon\Carbon;
use Devertix\LaravelApiGenerator\Generators\Config\ConfigStore;
use Faker\Generator;

class TestGenerator extends GeneratorAbstract
{
    private $fieldsWithValue = [];

    public function __construct(ConfigStore $config, $stubDirectory, $destinationDirectory, Generator $faker)
    {
        parent::__construct($config, $stubDirectory, $destinationDirectory, $faker);
        foreach ($this->fields as $delta => $fieldData) {
            $this->fieldsWithValue[$delta] = $this->generateValues($fieldData);
        }
    }

    protected function getStubFileName()
    {
        return 'Test.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Test.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        foreach ($this->fieldsWithValue as $delta => $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "' => '" . $fieldData['value'] . "',", 5);
        }
        $this->stringsToReplace['%%createtest_custom_fields_code%%'] = $code;
        $code = '';
        foreach ($this->fieldsWithValue as $fieldData) {
            $code .= $this->indentString("\$this->assertEquals('" . $fieldData['value'] . "', \$entity->" . $fieldData['name'] . ");", 2);
            $code .= $this->indentString("\$this->assertEquals('" . $fieldData['value'] . "', \$responseData['data']['attributes']['" . $fieldData['name'] . "']);", 2);
        }
        $this->stringsToReplace['%%createtest_custom_assert_code%%'] = $code;
        $code = '';
        foreach ($this->fieldsWithValue as $delta => $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "' => '" . $fieldData['new value'] . "',", 5);
        }
        $this->stringsToReplace['%%updatetest_custom_fields_code%%'] = $code;
        $code = '';
        foreach ($this->fieldsWithValue as $fieldData) {
            $code .= $this->indentString("\$this->assertEquals('" . $fieldData['new value'] . "', \$entity->" . $fieldData['name'] . ");", 2);
            $code .= $this->indentString("\$this->assertEquals('" . $fieldData['new value'] . "', \$responseData['data']['attributes']['" . $fieldData['name'] . "']);", 2);
        }
        $this->stringsToReplace['%%updatetest_custom_assert_code%%'] = $code;
    }

    private function generateValues($fieldData)
    {
        switch ($fieldData['type']) {
            case 'integer':
                $fieldData['value'] = $this->faker->numberBetween(1, 10000);
                $fieldData['new value'] = $fieldData['value'] + 2;
                break;
            case 'string':
                $fieldData['value'] = $this->faker->words(3, true);
                $fieldData['new value'] = $fieldData['value'] . ' modified';
                break;
            case 'boolean':
                $fieldData['value'] = $this->faker->randomElement([1, 0]);
                $fieldData['new value'] = abs($fieldData['value'] - 1);
                break;
            case 'text':
                $fieldData['value'] = $this->faker->text(40);
                $fieldData['new value'] = $fieldData['value'] . 'modified';
                break;
            case 'datetime':
                $fieldData['value'] = '2020-01-01 10:00:00';
                $fieldData['new value'] = '2020-01-02 10:00:00';
                break;
            default:
                throw $this->invalidFieldTypeException($fieldData['type']);
                break;
        }
        return $fieldData;
    }
}
