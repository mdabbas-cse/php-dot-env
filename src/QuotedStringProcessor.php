<?php

namespace MAU_CSE\DotEnv;

use MAU_CSE\DotEnv\ProcessorAbstract;

class QuotedStringProcessor extends ProcessorAbstract
{
  /**
   * The list of boolean values
   */
  public function canBeProcessed(): bool
  {
    $wrappedByDoubleQuotes = $this->isWrappedByChar($this->value, '"');

    if ($wrappedByDoubleQuotes)  return true;

    return $this->isWrappedByChar($this->value, '\'');
  }

  public function execute()
  {
    return substr($this->value, 1, -1);
  }

  private function isWrappedByChar(string $value, string $char): bool
  {
    return !empty($value) && $value[0] === $char && $value[-1] === $char;
  }
}
