<?php

namespace Omnipay\Exact\Message;

use \Omnipay\Common\Message\ResponseInterface;
use \Omnipay\Common\Message\RequestInterface;
use \Guzzle\Http\Message\Response as HttpResponse;

class ErrorResponse implements ResponseInterface
{
    protected $request;
    protected $httpResponse;

    public function __construct(RequestInterface $request, HttpResponse $httpResponse)
    {
        $this->request = $request;
        $this->httpResponse = $httpResponse;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return false;
    }

    public function isCancelled()
    {
        return false;
    }

    public function getMessage()
    {
        return $this->httpResponse->getBody(true);
    }

    public function getCode()
    {
        return $this->httpResponse->getStatusCode();
    }

    public function getTransactionReference()
    {
        return null;
    }

    public function getData()
    {
        return $this->httpResponse->getMessage();
    }
}
