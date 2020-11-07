<?php

namespace addons\epay\library;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    public function __toString()
    {
        return $this->getContent();
    }
}
