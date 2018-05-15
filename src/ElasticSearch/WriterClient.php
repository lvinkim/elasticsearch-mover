<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 4/17/18
 * Time: 4:24 PM
 */

namespace App\ElasticSearch;

use Elasticsearch\ClientBuilder;

class WriterClient
{
    /**
     * @var \Elasticsearch\Client
     */
    static private $client;

    public static function createIndex($connectionParams, $index, $type, $properties)
    {
        self::initialClient($connectionParams);

        $params = [
            'index' => $index,
            'body' => [
                'mappings' => [
                    $type => [
                        'properties' => $properties
                    ],
                ],
            ],
        ];

        self::$client->indices()->create($params);
    }

    public static function putMapping($connectionParams, $index, $type, $properties)
    {
        self::initialClient($connectionParams);

        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                $type => [
                    'properties' => $properties,
                ],
            ],
        ];

        self::$client->indices()->putMapping($params);
    }

    public static function indexMultiDocuments($connectionParams, $index, $type, $documents)
    {

        self::initialClient($connectionParams);

        $number = 0;
        $params = ['body' => []];
        foreach ($documents as $document) {

            $_id = $document['_id'] ?? false;
            $_source = $document['_source'] ?? [];
            if (!$_id || !$_source) {
                continue;
            }

            $number++;

            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type,
                    '_id' => $_id
                ]
            ];

            $params['body'][] = $_source;

            if ($number >= 1000) {
                self::$client->bulk($params);
                $number = 0;
                $params = ['body' => []];
            }
        }

        // Send the last batch if it exists
        if ($number > 0) {
            self::$client->bulk($params);
        }
    }

    public static function updateDocument($connectionParams, $index, $type, $id, $updateSet)
    {
        self::initialClient($connectionParams);

        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => [
                'doc' => $updateSet
            ]
        ];

        $response = self::$client->update($params);
        return $response;
    }

    public static function initialClient($connectionParams)
    {
        if (!is_array($connectionParams)) {
            settype($connectionParams, 'array');
        }

        if (null === self::$client) {
            self::$client = ClientBuilder::create()
                ->setHosts($connectionParams)
                ->build();
        }

        return self::$client;
    }
}