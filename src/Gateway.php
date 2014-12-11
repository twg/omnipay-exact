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
}
