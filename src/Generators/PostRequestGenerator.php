<?php

namespace Devertix\LaravelApiGenerator\Generators;

class PostRequestGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'PostRequest.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'PostRequest.php';
    }

    protected function extendReplaceData()
    {
    }
}
