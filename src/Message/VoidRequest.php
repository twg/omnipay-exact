<?php

namespace Omnipay\Exact\Message;

class VoidRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        return '13';
    }
}
