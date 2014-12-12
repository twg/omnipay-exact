<?php

namespace Omnipay\Exact\Message;

class PurchaseRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        return '00';
    }
}
