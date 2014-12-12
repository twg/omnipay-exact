<?php

namespace Omnipay\Exact\Message;

class RefundRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        return '04';
    }
}
