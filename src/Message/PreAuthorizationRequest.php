<?php

namespace Omnipay\Exact\Message;

class PreAuthorizationRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        return '01';
    }
}
