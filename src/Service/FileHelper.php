<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 3:52 PM
 */

namespace App\Service;


class FileHelper
{
    public static function readLines($input)
    {
        $inputPath = self::getPath($input);
        if (!is_file($inputPath)) {
            $lines = [];
        } else {
            $lines = self::read($inputPath);
        }

        foreach ($lines as $line) {
            if (!trim($line)) {
                continue;
            }
            $lineDecoded = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) continue;
            yield $lineDecoded;
        }

    }

    public static function read($filename)
    {
        $fp = fopen($filename, 'r');
        $chunk = 40960;
        while ($fp && !feof($fp)) {
            while (($line = fgets($fp, $chunk)) !== false) {
                yield $line;
            }
        }
    }

    public static function fetch($input)
    {
        $inputPath = self::getPath($input);
        if (!is_file($inputPath)) {
            return [];
        }
        $content = file_get_contents($inputPath);

        return json_decode($content, true);
    }

    public static function append($output, $content)
    {
        $outputPath = self::getPath($output);
        file_put_contents($outputPath, json_encode($content) . PHP_EOL, FILE_APPEND);
    }

    public static function save($output, $content)
    {
        $outputPath = self::getPath($output);
        file_put_contents($outputPath, json_encode($content, JSON_PRETTY_PRINT));
    }

    public static function getPath($output)
    {
        $outputPath = __DIR__ . '/../../var/output/' . $output;
        return $outputPath;
    }
}