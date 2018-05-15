<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 4/17/18
 * Time: 4:24 PM
 */

namespace App\ElasticSearch;

use Elasticsearch\ClientBuilder;

class ReaderClient
{
    /**
     * @var \Elasticsearch\Client
     */
    static private $client;

    public static function existsType($connectionParams, $index, $type)
    {
        self::initialClient($connectionParams);
        $params = [
            'index' => $index,
            'type' => $type,
        ];
        $response = self::$client->indices()->existsType($params);

        return $response;
    }

    public static function getMapping($connectionParams, $index, $type)
    {
        self::initialClient($connectionParams);
        $params = [
            'index' => $index,
            'type' => $type,
        ];
        $response = self::$client->indices()->getMapping($params);

        $properties = $response[$index]['mappings'][$type]['properties'] ?? [];

        return $properties;

    }

    public static function traversalDocuments($connectionParams, $index, $type, $body = null)
    {
        self::initialClient($connectionParams);

        if (!$body) {
            // 默认遍历所有记录
            $body = [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ];
        }

        $params = [
            "scroll" => "30s",
            "size" => 1000,
            'index' => $index,
            'type' => $type,
            'body' => $body
        ];

        $response = self::$client->search($params);

        do {
            if (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {
                $documents = $response['hits']['hits'];
                foreach ($documents as $document) {
                    yield $document;
                }

                $scroll_id = $response['_scroll_id'];
                $response = self::$client->scroll([
                        "scroll_id" => $scroll_id,
                        "scroll" => "30s"
                    ]
                );
            } else {
                break;
            }
        } while (1);
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