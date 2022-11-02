<?php

namespace MAU_CSE;

use MAU_CSE\ProcessorAbstract;

class BooleanProcessor extends ProcessorAbstract
{
  /**
   * The list of boolean values
   */
  public function canBeProcessed(): bool
  {
    $loweredValue = strtolower($this->value);
    return in_array($loweredValue, ['true', 'false'], true);
  }

  /**
   * Execute the processor
   * @return bool
   */
  public function execute()
  {
    return strtolower($this->value) === 'true';
  }
}
