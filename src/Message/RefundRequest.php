<?php

namespace Omnipay\Exact\Message;

class RefundRequest extends AbstractRequest
{
    protected function getTransactionTypeCode()
    {
        if ($this->isTagged()) {
            return '34';
        } else {
            return '04';
        }
    }
}
