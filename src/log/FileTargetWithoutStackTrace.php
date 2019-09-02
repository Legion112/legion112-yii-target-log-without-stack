<?php
namespace legion112\log;

use yii\helpers\VarDumper;
use yii\log\FileTarget;

/**
 * This file target service for info logging purpose
 * @example
 * if ($this->enableLogging) {
 *      Yii::info('Response from API', ApiService::class)
 * }
 *
 * in the config
 * [
 *     'class' => \legion112\log\FileTargetWithoutStackTrace::class,
 *     'categories' = [
 *          ApiService::class
 *     ]
 * ]
 * Class FileTargetWithoutStackTrace
 * @package legion112\log
 */
class FileTargetWithoutStackTrace extends FileTarget {
    /** @var string The directory the log going to store */
    public $logDir;

    /**
     * FileTargetWithoutStackTrace constructor.
     * @param array $config
     * @throws LogFileNotConfigureException
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['categories'])) {
            throw new CategoriesConfigureException('No category specify');
        }
        if (count($config['categories']) > 1) {
            throw new CategoriesConfigureException('Must be only one category');
        }
        if (!isset($config['logDir'])) {
            throw new LogDirConfigureException('logDir Must be specify');
        }

        if (!isset($config['logFile'])) {
            $class = current($config['categories']);
            $config['logFile'] = $config['logDir'] . str_replace('\\', '_', "$class.log");
        }

        parent::__construct($config);
    }

    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Throwable || $text instanceof \Exception) {
                $text = (string) $text;
            } else {
                $text = VarDumper::export($text);
            }
        }

        $prefix = $this->getMessagePrefix($message);
        return $this->getTime($timestamp) . " {$prefix} $text";
    }
}