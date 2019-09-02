<?php

namespace legion112\log;

class LogFileNotConfigureException extends \Exception
{
    protected $message = 'You must configure logFile';
}