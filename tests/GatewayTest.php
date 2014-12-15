<?php

namespace Omnipay\Exact;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize(array(
          'username' => 'test',
          'password' => 'test',
          'testMode' => true
        ));
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $request = $this->gateway->purchase(array(
          'amount' => '10.00',
          'orderId' => '123',
          'card'   => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET152556', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testBadRequest()
    {
        $this->setMockHttpResponse('BadRequest.txt');
        $request = $this->gateway->purchase(array(
          'amount' => '10.00',
          'orderId' => '123',
          'card'   => 'zzz'
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(null, $response->getTransactionReference());
        $this->assertSame(400, $response->getCode());
        $this->assertSame('Bad Request (22) - Invalid Credit Card Number', $response->getMessage());
    }

    public function testInvalidExpDate()
    {
        $this->setMockHttpResponse('InvalidExpDate.txt');
        $request = $this->gateway->authorize(array(
            'amount' => '1.00',
            'orderId' => '123',
            'card' => [
                'number' => '4111111111111111',
                'expiryMonth' => '',
                'expiryYear' => '',
                'cvv' => '',
                'firstName' => 'Test',
                'lastName' => 'User',
            ]
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\PreAuthorizationRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(null, $response->getTransactionReference());
        $this->assertFalse($response->isApproved());
        $this->assertSame('205', $response->getCode());
        $this->assertSame('Invalid Exp. Date', $response->getMessage());
    }

    public function testPreAuthorizationSuccess()
    {
        $this->setMockHttpResponse('PreAuthorizationSuccess.txt');
        $request = $this->gateway->authorize(array(
            'amount'  => '1.00',
            'orderId' => '123',
            'card'    => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\PreAuthorizationRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET136846', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }
}
