<?php

namespace Cierra\ConnectSdk;

use Cierra\ConnectSdk\DataDecorators\DataDecoratorInterface;
use Cierra\ConnectSdk\DataDecorators\ListDataDecorator;
use Cierra\ConnectSdk\DataDecorators\SingleItemDataDecorator;

class ConnectConfig
{
    private string $cierraApiUrl;
    private string $cierraConnectKey;
    private string $connection;

    private DataDecoratorInterface $listDataDecorator;
    private DataDecoratorInterface $singleItemDataDecorator;

    public function __construct(string $cierraApiUrl, string $cierraConnectKey, string $connection)
    {
        $this->cierraApiUrl = $cierraApiUrl;
        $this->cierraConnectKey = $cierraConnectKey;
        $this->connection = $connection;

        $this->listDataDecorator = new ListDataDecorator;
        $this->singleItemDataDecorator = new SingleItemDataDecorator;
    }

    public function getCierraApiUrl(): string
    {
        return $this->cierraApiUrl;
    }

    public function getCierraConnectKey(): string
    {
        return $this->cierraConnectKey;
    }

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function getListDataDecorator(): DataDecoratorInterface
    {
        return $this->listDataDecorator;
    }

    public function setListDataDecorator(DataDecoratorInterface $listDataDecorator): void
    {
        $this->listDataDecorator = $listDataDecorator;
    }

    public function getSingleItemDataDecorator(): DataDecoratorInterface
    {
        return $this->singleItemDataDecorator;
    }

    public function setSingleItemDataDecorator(DataDecoratorInterface $singleItemDataDecorator): void
    {
        $this->singleItemDataDecorator = $singleItemDataDecorator;
    }
}
