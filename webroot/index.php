<?php
spl_autoload_register(
    function($className)
    {
        $className = str_replace("_", "\\", $className);
        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\'))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require $fileName;
    }
);

class RequestIdTagger
{
    private $request_id;

    public function __construct()
    {
      $this->request_id=uniqid();
    }

    public function __invoke(array $record)
    {
        $record['extra']['request_id'] = $this->request_id;

        return $record;
    }
}


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Processor\IntrospectionProcessor;

$log = new Logger('name');
$log->pushProcessor(new RequestIdTagger());
$log->pushProcessor(new IntrospectionProcessor());
$logHandler=new StreamHandler('php://stderr', Logger::WARNING);
$logHandler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES,true));
$log->pushHandler($logHandler);

$log->error("This is a message");
$log->warning("This is a multiline \n message");
$log->error("This is message #3");


?>
