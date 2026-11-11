<?php

class CandlePatternRouting extends sfPatternRouting
{
    public function parse($url)
    {
        if (CandleArchive::isActive()) {
            $url = $this->normalizeUrl($url);
            $prefix = '/'.CandleArchive::getPrefix();
            // The prefix can be percent-encoded in an incoming request.
            $slash = strpos($url, '/', 1);
            $segment = $slash === false ? $url : substr($url, 0, $slash);
            if (rawurldecode($segment) !== $prefix) {
                return false;
            }
            $url = $slash === false ? '/' : substr($url, $slash);
        }
        return parent::parse($url);
    }
}
