<?php

namespace Omnipay\Exact;

use Omnipay\Common\AbstractGateway;

/**
 * Exact Gateway
 *
 * @link https://hostedcheckout.zendesk.com/forums
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Exact';
    }

    public function getDefaultParameters()
    {
        return array(
            'username'         => '',
            'password'         => '',
            'testMode'         => false,
        );
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

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Exact\Message\PurchaseRequest', $parameters);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Exact\Message\PreAuthorizationRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Exact\Message\RefundRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Exact\Message\PreAuthorizationCompletionRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Exact\Message\VoidRequest', $parameters);
    }
}
