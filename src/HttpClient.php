<?php

namespace Cierra\ConnectSdk;

use Cierra\ConnectSdk\Exceptions\ApiErrorResponseException;
use Cierra\ConnectSdk\Exceptions\CierraConnectInternalException;
use Cierra\ConnectSdk\Exceptions\CierraConnectUnavailableException;
use Cierra\ConnectSdk\Exceptions\ConnectionNotFoundException;
use Cierra\ConnectSdk\Exceptions\EntityActionNotFoundException;
use Cierra\ConnectSdk\Exceptions\EntityNotFoundException;
use Cierra\ConnectSdk\Exceptions\InvalidArgumentException;
use Cierra\ConnectSdk\Exceptions\NotAuthorizedException;
use Cierra\ConnectSdk\Exceptions\PlatformNotFoundException;
use Cierra\ConnectSdk\Exceptions\RequestMissedParamException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Throwable;

class HttpClient
{
    public static string $RESPONSE_TYPE_JSON = 'json';
    public static string $RESPONSE_TYPE_TEXT = 'text';

    private string $cierraApiUrl;
    private string $cierraConnectKey;

    public function __construct(string $cierraApiUrl, string $cierraConnectKey)
    {
        $this->cierraApiUrl = $cierraApiUrl;
        $this->cierraConnectKey = $cierraConnectKey;
    }

    public function request(string $connection, string $entity, string $entityAction, array $payload = []): array
    {
        try {
            $client = new Client();
            $response = $client->request('POST', $this->cierraApiUrl, [
                'form_params' => [
                    'cierra_connect_key' => $this->cierraConnectKey,
                    'connection' => $connection,
                    'entity' => $entity,
                    'entity_action' => $entityAction,
                    'payload' => $payload,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 400) {
                $responseData = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($responseData, true);
                $errorType = $responseData['error']['type'] ?? null;
                $errorMessage = $responseData['error']['message'] ?? null;

                switch ($errorType) {
                    case 'connection_not_found_exception':
                        throw new ConnectionNotFoundException($errorMessage);
                    case 'entity_not_found_exception':
                        throw new EntityNotFoundException($errorMessage);
                    case 'entity_action_not_found_exception':
                        throw new EntityActionNotFoundException($errorMessage);
                    case 'not_authorized_exception':
                        throw new NotAuthorizedException($errorMessage);
                    case 'platform_not_found_exception':
                        throw new PlatformNotFoundException($errorMessage);
                    case 'invalid_argument_exception':
                        throw new InvalidArgumentException($errorMessage);
                    case 'request_missed_param_exception':
                        throw new RequestMissedParamException($errorMessage);
                    case 'adapter_request_exception':
                        throw new ApiErrorResponseException($errorMessage);
                    default:
                        throw new CierraConnectInternalException($errorMessage);
                }
            } else {
                throw new CierraConnectInternalException;
            }
        } catch (ServerException|Throwable $e) {
            throw new CierraConnectUnavailableException;
        }
    }
}
