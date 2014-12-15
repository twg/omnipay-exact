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
        $this->assertSame(982025823, $response->getTransactionTag());
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

    public function testPreAuthorizationCompletionSuccess()
    {
        $this->setMockHttpResponse('TaggedPreAuthorizationCompletionSuccess.txt');
        $request = $this->gateway->capture(array(
            'amount' => '1.00',
            'orderId' => '123',
            'authorizationNum' => 'ET129831',
            'transactionTag' => '982026537'
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\PreAuthorizationCompletionRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET153279', $response->getTransactionReference());
        $this->assertSame(982026540, $response->getTransactionTag());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testRefundCardSuccess()
    {
        $this->setMockHttpResponse('RefundCardSuccess.txt');
        $request = $this->gateway->refund(array(
            'amount'  => '1.00',
            'orderId' => '123',
            'card'    => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\RefundRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET113682', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testTaggedRefundSuccess()
    {
        $this->setMockHttpResponse('TaggedRefundSuccess.txt');
        $request = $this->gateway->refund(array(
            'amount' => '1.00',
            'orderId' => '123',
            'authorizationNum' => 'ET158245',
            'transactionTag' => '982026486'
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\RefundRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET144818', $response->getTransactionReference());
        $this->assertSame(982026492, $response->getTransactionTag());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testTaggedVoidSuccess()
    {
        $this->setMockHttpResponse('TaggedVoidSuccess.txt');
        $request = $this->gateway->void(array(
            'amount' => '1.00',
            'orderId' => '123',
            'authorizationNum' => 'ET121810',
            'transactionTag' => '982026549'
        ));

        $this->assertInstanceOf('\Omnipay\Exact\Message\VoidRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ET182580', $response->getTransactionReference());
        $this->assertSame(982026561, $response->getTransactionTag());
        $this->assertTrue($response->isApproved());
        $this->assertSame('000', $response->getCode());
        $this->assertSame('Approved', $response->getMessage());
    }
}
