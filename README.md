# Cierra Connect PHP SDK

The Cierra Connect PHP SDK provides an easy way to integrate with the Cierra Connect API in your PHP applications.

## Installation

Install the package using Composer:

    ```sh
    composer require cierrateam/connect-php-sdk
    ```

## Usage Example

Here's an example of how you can use the Cierra Connect PHP SDK:

```php
<?php

use Cierra\ConnectSdk\Connect;
use Cierra\ConnectSdk\ConnectConfig;

$config = new ConnectConfig(
   'https://connect.cierra.io/api/gateway',
   'your_cierra_connect_account_key',
   'api_connection_key'
);

$apiInstance = new Connect($config);

// Get the first page of items list
$itemsList = $apiInstance->entity('entity_a')->list();
var_dump($itemsList);

// Get 4th page with 30 items
$paginatedList = $apiInstance->entity('entity_a')->list(4, 30);
var_dump($paginatedList);

// Get item by ID
$singleItem = $apiInstance->entity('entity_a')->get(5);
var_dump($singleItem);

// Set request params
$singleItem = $apiInstance->entity('entity_a')->setParam('query_param_a', 'value_a')->get(5);
var_dump($singleItem);

// Update item with ID 5
$singleItem = $apiInstance->entity('entity_a')->update(5, ['prop_a' => 'value_a']);
var_dump($singleItem);

// Create a new item
$singleItem = $apiInstance->entity('entity_a')->create(['prop_a' => 'value_a']);
var_dump($singleItem);

// Trigger API event
$result = $apiInstance->entity('entity_a')->triggerEvent('execute_function_a', ['param_a' => 'value_a']);
var_dump($result);

```
