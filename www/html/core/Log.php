<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Log.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class Log
{

  private $log_path;
  private $debug;

  /**
   * @param $path
   * @param $debug
   */
  public function __construct($path, $debug)
  {
    $this->log_path = $path;
    $this->debug = $debug;
  }

  /**
   * @return Logger
   */
  public function get()
  {

    $stream = null;
    $log = new Logger('WEB');

    if ($this->debug) {
      $stream = new StreamHandler(
        $this->log_path . '/app_' . date('Ymd') . '_dev.log',
        Logger::DEBUG
      );
    } else {
      $stream = new StreamHandler(
        $this->log_path . '/app_' . date('Ymd') . '.log',
        Logger::INFO
      );
    }

    $formatter = new LineFormatter(null, null, true);
    $stream->setFormatter($formatter);

    $log->pushHandler($stream);

    return $log;

  }

  /**
   * @param $path
   * @param $debug
   *
   * @return Log
   */
  static public function create($path, $debug)
  {
    return new self($path, $debug);
  }
}
