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

    public static function indexMultiDocuments($connectionParams, $index, $type, $documents)
    {

        self::initialClient($connectionParams);

        $number = 0;
        $params = ['body' => []];
        foreach ($documents as $document) {
            $number++;

            if (!isset($document['id'])) {
                continue;// Document must have key : 'id'.
            }

            $id = $document['id'];
//            unset($document['id']);

            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type,
                    '_id' => $id
                ]
            ];

            $params['body'][] = $document;

            if ($number % 1000 == 0) {
                self::$client->bulk($params);
                $params = ['body' => []];
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
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