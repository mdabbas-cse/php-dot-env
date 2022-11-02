<?php

namespace MAU_CSE;

abstract class ProcessorAbstract implements ProcessorInterface
{
  /**
   * The value to process
   * @var string
   */
  protected $value;

  public function __construct(string $value)
  {
    $this->value = $value;
  }
}
