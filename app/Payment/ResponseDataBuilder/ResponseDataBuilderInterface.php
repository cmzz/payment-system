<?php
declare(strict_types=1);


namespace App\Payment\ResponseDataBuilder;


use App\Models\Charge;
use Omnipay\Common\Message\ResponseInterface;

interface ResponseDataBuilderInterface
{
    public function __construct(Charge $charge, ResponseInterface $response);

    public function getData(): array;
}
