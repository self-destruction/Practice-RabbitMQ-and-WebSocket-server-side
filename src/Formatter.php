<?php

namespace App;

/**
 * Class Formatter
 * Класс для форматирования данных
 */
class Formatter
{
    /**
     * Метод, выполняющий декодирование, разжатие и десериализацию
     * строки для передачи по каналу
     * @param string $convertedData
     * @return array
     */
    public static function revertConvertedData(string $convertedData): array {
        $serializedData = base64_decode($convertedData);

        $message = unserialize($serializedData);

        $uncompressedData = gzdecode($message['data'], $message['length']);

        return unserialize($uncompressedData);
    }
}