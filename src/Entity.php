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

    private function defaultResultType(array $apiResponseData)
    {
        if ($this->asRaw) {
            return $apiResponseData['response'];
        } else {
            if ($apiResponseData['response']['response_type'] === 'json') {
                $decodedResponse = json_decode($apiResponseData['response']['response_data'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decodedResponse;
                }
            }
        }

        return $apiResponseData['response']['response_data'];
    }

    public function setParam(string $key, $value): Entity
    {
        $this->params[$key] = $value;
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

    public function create(array $properties = [])
    {
        $apiResponseData = $this->connect->doApiCall($this->name, 'create', [
            'entity_props' => $properties,
            'params' => $this->params,
        ]);

        return $this->defaultResultType($apiResponseData);
    }

    public function update($id, array $properties)
    {
        $apiResponseData = $this->connect->doApiCall($this->name, 'update', [
            'entity_id' => (string)$id,
            'entity_props' => $properties,
            'params' => $this->params,
        ]);

        return $this->defaultResultType($apiResponseData);
    }

    public function triggerEvent(string $name, array $params = [])
    {
        $apiResponseData = $this->connect->doApiCall($this->name, 'trigger_event', [
            'event_name' => $name,
            'params' => $params ?: $this->params,
        ]);

        return $this->defaultResultType($apiResponseData);
    }
}
