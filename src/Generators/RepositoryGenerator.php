<?php

namespace Devertix\LaravelApiGenerator\Generators;

class RepositoryGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'Repository.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Repository.php';
    }

    protected function extendReplaceData()
    {
        $code = $this->indentString('protected $allowedFilters = [', 1);

        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "',", 2);
        }
        $code .= $this->indentString('];', 1);
        $code .= $this->indentString('protected $allowedOrders = [', 1);
        $code .= $this->indentString("'id',", 2);
        if ($this->timestamps) {
            $code .= $this->indentString("'created_at',", 2);
            $code .= $this->indentString("'updated_at',", 2);
        }
        foreach ($this->fields as $fieldData) {
            if ($fieldData['type'] == 'text') {
                continue;
            }
            $code .= $this->indentString("'" . $fieldData['name'] . "',", 2);
        }
        $code .= $this->indentString('];', 1);
        $code .= $this->indentString('protected function filterQuery($filterName, $filterValue, $query)', 1);
        $code .= $this->indentString('{', 1);
        $code .= $this->indentString('switch ($filterName) {', 2);
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'string':
                case 'text':
                    $code .= $this->indentString("case '" . $fieldData['name'] . "':", 3);
                    $code .= $this->indentString("return \$query->where('" . $fieldData['name'] . "', 'LIKE', '%' . \$filterValue . '%');", 4);
                    break;
                case 'integer':
                    $code .= $this->indentString("case '" . $fieldData['name'] . "':", 3);
                    $code .= $this->indentString("return \$query->where('" . $fieldData['name'] . "', \$filterValue);", 4);
                    break;
                case 'boolean':
                    $code .= $this->indentString("case '" . $fieldData['name'] . "':", 3);
                    $code .= $this->indentString("if (abs(\$filterValue)) {", 4);
                    $code .= $this->indentString("return \$query->where('" . $fieldData['name'] . "', \$filterValue == 1 ? true : false);", 5);
                    $code .= $this->indentString('}', 4);
                    $code .= $this->indentString('return $query;', 4);
                    break;
                case 'datetime':
                    break;
                default:
                    throw $this->invalidFieldTypeException($fieldData['type']);
                    break;
            }
        }
        $code .= $this->indentString('default:', 3);
        $code .= $this->indentString('return $query;', 4);
        $code .= $this->indentString('}', 2);
        $code .= $this->indentString('}', 1);

        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
