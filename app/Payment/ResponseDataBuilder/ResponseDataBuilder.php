<?php
declare(strict_types=1);


namespace App\Payment\ResponseDataBuilder;


use App\Models\Charge;
use Omnipay\Common\Message\ResponseInterface;

class ResponseDataBuilder implements ResponseDataBuilderInterface
{
    /** @var Charge */
    protected $charge;

    /** @var ResponseInterface */
    protected $response;

    /** @var array */
    protected $data;

    public function __construct(Charge $charge, ResponseInterface $response)
    {
        $this->charge = $charge;
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
