<?php

namespace Cierra\ConnectSdk\DataDecorators;

interface DataDecoratorInterface
{
    public function decorate(array $apiResponseData);
}
