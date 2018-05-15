<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 15/05/2018
 * Time: 9:47 PM
 */

namespace App\Service;


class IndexMover
{
    public static function move($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType)
    {
        $documents = \App\ElasticSearch\ReaderClient::traversalDocuments($fromHost, $fromIndex, $fromType);

        $bulkDocuments = [];
        $cursor = 0;
        foreach ($documents as $document) {
            $cursor++;
            echo $cursor . PHP_EOL;
            $bulkDocuments[] = $document;
            if (count($bulkDocuments) >= 1000) {
                \App\ElasticSearch\WriterClient::indexMultiDocuments($toHost, $toIndex, $toType, $bulkDocuments);
                $bulkDocuments = [];
            }
        }
        if (count($bulkDocuments)) {
            \App\ElasticSearch\WriterClient::indexMultiDocuments($toHost, $toIndex, $toType, $bulkDocuments);
        }
    }
}