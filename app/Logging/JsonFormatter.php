<?php
declare(strict_types=1);

namespace App\Logging;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

class JsonFormatter extends BaseJsonFormatter
{
    public function format(array $record)
    {
        $newRecord = [
            'time' => $record['datetime']->format('Y-m-d H:i:s'),
            'message' => $record['message'],
        ];

        if (!empty($record['context'])) {
            $newRecord = array_merge($newRecord, $record['context']);
        }

        $json = $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');

        return $json;
    }
}
