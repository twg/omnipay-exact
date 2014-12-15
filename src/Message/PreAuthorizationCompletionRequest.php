<?php

namespace Omnipay\Exact\Message;

class PreAuthorizationCompletionRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        return '32';
    }
}
