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

    public static function export($host, $index, $type, $output, $limit = 0)
    {
        $documents = ReaderClient::traversalDocuments($host, $index, $type);

        $cursor = 0;
        foreach ($documents as $document) {
            $cursor++;
            echo $cursor . PHP_EOL;
            if ($limit && $cursor > $limit) {
                break;
            }
            FileHelper::append($output, $document);
        }
    }

    public static function import($host, $index, $type, $input)
    {
        $documents = FileHelper::readLines($input);

        $bulkDocuments = [];
        $cursor = 0;
        foreach ($documents as $document) {
            $cursor++;
            echo $cursor . PHP_EOL;
            $bulkDocuments[] = $document;
            if (count($bulkDocuments) >= 1000) {
                WriterClient::indexMultiDocuments($host, $index, $type, $bulkDocuments);
                $bulkDocuments = [];
            }
        }
        if (count($bulkDocuments)) {
            WriterClient::indexMultiDocuments($host, $index, $type, $bulkDocuments);
        }
    }
}