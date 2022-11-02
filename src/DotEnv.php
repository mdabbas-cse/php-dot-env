<?php

namespace MAU_CSE;

use MAU_CSE\ProcessorAbstract;
use MAU_CSE\BooleanProcessor;
use MAU_CSE\QuotedStringProcessor;

class DotEnv
{
  /**
   * The path to the .env file
   * @var string
   */
  protected $path;

  /**
   * The processors to use
   * @var array
   */
  protected $processors = [];

  /**
   * The constructor
   * @param string $path
   */
  public function __construct(string $path, array $processors = null)
  {
    /**
     * Set the path
     */
    if (!file_exists($path)) {
      throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }
    $this->path = $path;
    $this->setProcessors($processors);
  }

  private function setProcessors(array $processors = null): DotEnv
  {

    if (Is_null($processors)) {
      $this->processors = [
        BooleanProcessor::class,
        QuotedStringProcessor::class
      ];

      return $this;
    }

    foreach ($processors as $processor) {
      if (is_subclass_of($processor, ProcessorAbstract::class)) {
        $this->processors[] = $processor;
      }
    }

    return $this;
  }
  /**
   * Load the .env file
   * @return array
   */
  public function load(): void
  {
    /**
     * Read the file
     */
    if (!is_readable($this->path)) {
      throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
    }

    /**
     * Get the lines
     */
    $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      // Skip comments
      if (strpos(trim($line), '#') === 0) continue;
      // Split the line
      list($name, $value) = explode('=', $line, 2);
      // Trim the name
      $name = trim($name);
      // Trim the value
      $value = $this->processValue($value);
      // Set the environment variable
      if (!array_key_exists($name, $_ENV)) {
        // Set the environment variable
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
      }
    }
  }

  /**
   * Process the value
   * @param string $value
   * @return string
   */
  private function processValue(string $value)
  {
    $trimValue = trim($value);

    foreach ($this->processors as $processor) {
      $processorInstance = new $processor($trimValue);
      if ($processorInstance->canBeProcessed()) {
        return $processorInstance->execute();
      }
    }

    return $trimValue;
  }
}
