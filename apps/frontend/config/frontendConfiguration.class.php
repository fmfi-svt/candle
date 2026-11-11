<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function initialize()
  {
    CandleArchive::initialize($this->dispatcher);
  }
}
