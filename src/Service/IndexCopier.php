<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 15/05/2018
 * Time: 9:47 PM
 */

namespace App\Service;


use App\ElasticSearch\ReaderClient;
use App\ElasticSearch\WriterClient;

class IndexCopier
{
    public static function copy($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType)
    {
        $documents = ReaderClient::traversalDocuments($fromHost, $fromIndex, $fromType);

        $bulkDocuments = [];
        $cursor = 0;
        foreach ($documents as $document) {
            $cursor++;
            echo $cursor . PHP_EOL;
            $bulkDocuments[] = $document;
            if (count($bulkDocuments) >= 1000) {
                WriterClient::indexMultiDocuments($toHost, $toIndex, $toType, $bulkDocuments);
                $bulkDocuments = [];
            }
        }
        if (count($bulkDocuments)) {
            WriterClient::indexMultiDocuments($toHost, $toIndex, $toType, $bulkDocuments);
        }
    }
}