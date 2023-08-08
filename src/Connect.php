<?php

namespace Cierra\ConnectSdk;

use Cierra\ConnectSdk\Exceptions\CierraConnectSdkException;

class Connect
{
    private ConnectConfig $connectConfig;
    private HttpClient $httpClient;

    public function __construct(ConnectConfig $connectConfig)
    {
        $this->connectConfig = $connectConfig;
        $this->httpClient = new HttpClient($connectConfig->getCierraApiUrl(), $connectConfig->getCierraConnectKey());
    }

    public function entity(string $name): Entity
    {
        return new Entity($this, $this->connectConfig, $name);
    }

    /**
     * @throws CierraConnectSdkException
     */
    public function doApiCall(string $entity, string $entityAction, array $params = []): array
    {
        return $this->httpClient->request(
            $this->connectConfig->getConnection(),
            $entity,
            $entityAction,
            $params
        );
    }
}
