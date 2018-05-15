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

    /**
     * 聚合名必须为 buckets , 即必须有 $body['aggs']['buckets']['composite']['sources']
     * @param $connectionParams
     * @param $index
     * @param $type
     * @param $body
     * @return \Generator
     */
    public static function traversalAggregation($connectionParams, $index, $type, $body)
    {
        self::initialClient($connectionParams);

        $params = [
            'index' => $index,
            'type' => $type,
            'body' => $body
        ];
        $response = self::$client->search($params);

        do {
            $buckets = $response['aggregations']['buckets']['buckets'] ?? [];
            if (count($buckets) > 0) {
                foreach ($buckets as $bucket) {
                    yield $bucket;
                }
                $lastBucket = end($buckets);
                $body['aggs']['buckets']['composite']['after'] = $lastBucket['key'];

                $params['body'] = $body;
                $response = self::$client->search($params);
            } else {
                break;
            }
        } while (1);
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
            "size" => 500,
            'index' => $index,
            'type' => $type,
            'body' => $body
        ];

        $response = self::$client->search($params);

        do {
            if (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {
                $products = $response['hits']['hits'];
                foreach ($products as $product) {
                    yield $product;
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


    public static function searchByJson($connectionParams, $index, $type, $json)
    {

        self::initialClient($connectionParams);

        $params = [
            'index' => $index,
            'type' => $type,
            'body' => $json
        ];

        $results = self::$client->search($params);

        return $results;
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