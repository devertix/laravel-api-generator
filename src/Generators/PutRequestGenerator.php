<?php

namespace Devertix\LaravelApiGenerator\Generators;

class PutRequestGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'PutRequest.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'PutRequest.php';
    }

    protected function extendReplaceData()
    {
    }
}
