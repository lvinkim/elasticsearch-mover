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

class MappingCopier
{
    public static function copy($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType)
    {
        $properties = ReaderClient::getMapping($fromHost, $fromIndex, $fromType);

        $isExists = ReaderClient::existsType($toHost, $toIndex, $toType);

        if (!$isExists) {
            WriterClient::createIndex($toHost, $toIndex, $toType, $properties);
        } else {
            WriterClient::putMapping($toHost, $toIndex, $toType, $properties);
        }
    }

    public static function export($host, $index, $type, $output)
    {
        $properties = ReaderClient::getMapping($host, $index, $type);

        FileHelper::save($output, $properties);
    }

    public static function import($host, $index, $type, $input)
    {
        $properties = FileHelper::fetch($input);

        $isExists = ReaderClient::existsType($host, $index, $type);

        if (!$isExists) {
            WriterClient::createIndex($host, $index, $type, $properties);
        } else {
            WriterClient::putMapping($host, $index, $type, $properties);
        }
    }
}