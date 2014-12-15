<?php

namespace Omnipay\Exact\Message;

/**
 *  E-xact Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $testUrl = 'https://api-demo.e-xact.com/transaction';
    protected $liveUrl = 'https://api.e-xact.com/transaction';

    abstract protected function getTransactionTypeCode();

    public function getData()
    {
        $data = array(
            'gateway_id'        => $this->getUsername(),
            'password'          => $this->getPassword(),
            'transaction_type'  => $this->getTransactionTypeCode(),
            'amount'            => $this->getAmount(),
            'authorization_num' => $this->getAuthorizationNum(),
            'reference_no'      => $this->getOrderId()
        );

        if ($card = $this->getCard()) {
            $data['cc_number']             = $card->getNumber();
            $data['cc_expiry']             = $card->getExpiryDate('my');
            $data['cardholder_name']       = $card->getBillingName('my');
            $data['cc_verification_str1 '] = $this->calculateVerificationStr1($card);
            $data['cc_verification_str2 '] = $card->getCvv();
            $data['cvd_presence_ind']      = '1';
        }

        return json_encode($data);
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint(),
            $this->getHeaders(),
            $data,
            array(
              'exceptions' => false,
              'auth' => array(
                $this->getUsername(),
                $this->getPassword()
              )
            )
        )->send();

        return $this->createResponse($httpResponse);
    }

    protected function getHeaders()
    {
        return array(
            'Accept' => 'application/json',
            'Content-type' => 'application/json; charset=UTF-8'
        );
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testUrl : $this->liveUrl;
    }

    protected function createResponse($httpResponse)
    {
        if ($httpResponse->isError()) {
            return new ErrorResponse($this, $httpResponse);
        } else {
            return new Response($this, $httpResponse->json());
        }
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getAuthorizationNum()
    {
        return $this->getParameter('authorizationNum');
    }

    public function setAuthorizationNum($value)
    {
        return $this->setParameter('authorizationNum', $value);
    }

    protected function calculateVerificationStr1($card)
    {
        $result = '';
        return $result;
    }
}
