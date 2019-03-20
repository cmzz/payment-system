<?php
declare(strict_types=1);


namespace App\Payment\ResponseDataBuilder;


use App\Models\Recharge;
use Omnipay\Common\Message\ResponseInterface;

interface ResponseDataBuilderInterface
{
    public function __construct(Recharge $recharge, ResponseInterface $response);

    public function getData(): array;
}
