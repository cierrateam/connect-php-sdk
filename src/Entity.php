<?php

namespace Cierra\ConnectSdk;

class Entity
{
    private Connect $connect;
    private ConnectConfig $connectConfig;
    private string $name;
    private array $params = [];
    private bool $asRaw = false;

    public function __construct(Connect $connect, ConnectConfig $connectConfig, string $name)
    {
        $this->connect = $connect;
        $this->connectConfig = $connectConfig;
        $this->name = $name;
    }

    public function addParams(array $params): Entity
    {
        $this->params[] = array_merge($this->params, $params);
        return $this;
    }

    public function asRawResponse(bool $value = true): Entity
    {
        $this->asRaw = $value;
        return $this;
    }

    public function list(int $page = 1, int $perPage = 10)
    {
        $apiResponseData = $this->connect->doApiCall($this->name, 'list', [
            'pagination_page' => $page,
            'pagination_per_page' => $perPage,
            'params' => $this->params,
        ]);

        if ($this->asRaw) {
            return $apiResponseData['response'];
        } else {
            return $this->connectConfig->getListDataDecorator()->decorate($apiResponseData['response']);
        }
    }

    public function get($id)
    {
        $apiResponseData = $this->connect->doApiCall($this->name, 'get', [
            'entity_id' => (string)$id,
            'params' => $this->params,
        ]);

        if ($this->asRaw) {
            return $apiResponseData['response'];
        } else {
            return $this->connectConfig->getSingleItemDataDecorator()->decorate($apiResponseData['response']);
        }
    }

    public function create(array $props)
    {
        return $this->connect->doApiCall($this->name, 'create', [
            'entity_props' => $props,
            'params' => $this->params,
        ]);
    }

    public function update($id, array $props)
    {
        return $this->connect->doApiCall($this->name, 'create', [
            'entity_id' => (string)$id,
            'entity_props' => $props,
            'params' => $this->params,
        ]);
    }
}
