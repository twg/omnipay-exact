<?php

namespace Omnipay\Exact\Message;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    public function getTransactionReference()
    {
        return $this->data['authorization_num'];
    }

    public function isApproved()
    {
        return $this->data['transaction_approved'] == 1;
    }

    public function getMessage()
    {
        if ($this->data['error_number'] != 0) {
            return $this->data['error_description'];
        } elseif ($this->data['transaction_error'] != 0) {
            return $this->data['exact_message'];
        } else {
            return $this->data['bank_message'];
        }
    }

    public function getCode()
    {
        if ($this->data['error_number'] != 0) {
            return $this->data['error_number'];
        } elseif ($this->data['transaction_error'] != 0) {
            return $this->data['exact_resp_code'];
        } else {
            return $this->data['bank_resp_code'];
        }
    }

    public function isSuccessful()
    {
        return $this->data['error_number'] == 0 &&
          $this->data['transaction_error'] == 0;
    }
}
