<?php
namespace Joshbmarshall\PxPay;

use Joshbmarshall\PxPay\Action\AuthorizeAction;
use Joshbmarshall\PxPay\Action\CancelAction;
use Joshbmarshall\PxPay\Action\ConvertPaymentAction;
use Joshbmarshall\PxPay\Action\CaptureAction;
use Joshbmarshall\PxPay\Action\NotifyAction;
use Joshbmarshall\PxPay\Action\RefundAction;
use Joshbmarshall\PxPay\Action\StatusAction;
use Joshbmarshall\PxPay\Bridge\Spl\ArrayObject;
use Joshbmarshall\PxPay\GatewayFactory;

class PxPayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'pxpay',
            'payum.factory_title' => 'pxpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
