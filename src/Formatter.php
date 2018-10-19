<?php

namespace App;

class Formatter
{
    public static function convertData(array $arrayData): string {
        $serializedData = serialize($arrayData);

        $dataLength = strlen($serializedData);
        $compressedData = gzencode($serializedData);

        $serializedData = serialize(['data' => $compressedData, 'length' => $dataLength]);

        return base64_encode($serializedData);
    }

    public static function revertConvertedData(string $convertedData): array {
        $serializedData = base64_decode($convertedData);

        $message = unserialize($serializedData);

        $uncompressedData = gzdecode($message['data'], $message['length']);

        return unserialize($uncompressedData);
    }
}