<?php
namespace legion112\log;

use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

class FileTargetWithoutStackTrace extends FileTarget {
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Throwable || $text instanceof \Exception) {
                $text = (string) $text;
            } else {
                $text = VarDumper::export($text);
            }
        }

        $prefix = $this->getMessagePrefix($message);
        return $this->getTime($timestamp) . " {$prefix}[$level][$category] $text";
    }
}