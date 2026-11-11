<?php

class CandleWebRequest extends sfWebRequest
{
    public function getRequestContext()
    {
        $context = parent::getRequestContext();
        if (CandleArchive::isActive()) {
            // Only application URLs get this prefix; asset helpers keep the real web root.
            $context['prefix'] .= '/'.CandleArchive::getPrefix();
        }
        return $context;
    }
}
