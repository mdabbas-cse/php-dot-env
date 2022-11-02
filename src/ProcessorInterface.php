<?php

namespace MAU_CSE;

interface ProcessorInterface
{
  public function __construct(string $value);
  public function canBeProcessed(): bool;
  public function execute();
}
