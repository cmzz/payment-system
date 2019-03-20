<?php

declare(strict_types=1);


namespace App\Payment\ResponseDataBuilder;


use App\Models\Recharge;
use Omnipay\Common\Message\ResponseInterface;

class ResponseDataBuilder implements ResponseDataBuilderInterface
{
    /** @var Recharge */
    protected $recharge;

    /** @var ResponseInterface */
    protected $response;

    /** @var array */
    protected $data;

    public function __construct(Recharge $recharge, ResponseInterface $response)
    {
        $this->recharge = $recharge;
        $this->response = $response;
    }

    protected function process()
    {

    }

    public function getData(): array
    {
        $this->process();
        return $this->data;
    }
}
