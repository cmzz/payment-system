<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\InvalidArgumentException;
use App\Models\Recharge;
use App\Types\Channel;
use Illuminate\Foundation\Http\FormRequest;

class NewOrderRequest extends FormRequest
{
    const ORDER_NO = 'order_no';
    const AMOUNT = 'amount';
    const CLIENT_IP = 'client_ip';
    const CURRENCY = 'currency';
    const SUBJECT = 'subject';
    const CHANNEL = 'channel';
    const BODY = 'body';
    const APP_ID = 'app_id';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::ORDER_NO => 'required|string',
            self::AMOUNT => 'required|numeric|integer|min:1',
            self::CLIENT_IP => 'required|ipv4',
            self::CURRENCY => 'required|string',
            self::CHANNEL => 'required|string',
            self::SUBJECT => 'required|string|min:2',
        ];
    }

    private function getAmount(): int
    {
        $amount = $this->get(self::AMOUNT, 0);

        if ($amount < 1) {
            throw new InvalidArgumentException();
        }

        return $amount;
    }

    private function getChannel(): string
    {
        $channel = $this->get(self::CHANNEL);

        if (!in_array($channel, Channel::names())) {
            throw new InvalidArgumentException();
        }

        return $channel;
    }

    private function getCurrency(): string
    {
        // todo 目前仅支持cny
        return strtoupper('cny');
    }

    public function getAll()
    {
        $data = [
            Recharge::ORDER_NO => $this->get(self::ORDER_NO, 0),
            Recharge::AMOUNT => $this->getAmount(),
            Recharge::CHANNEL => $this->getChannel(),
            Recharge::CURRENCY => $this->getCurrency(),
            Recharge::CLIENT_IP => $this->get(self::CLIENT_IP),
            Recharge::SUBJECT => $this->get(self::SUBJECT),
            Recharge::BODY => $this->get(self::BODY),
            Recharge::APP_ID => current_app_id(),
        ];

        if (config('app.debug') == true && config('app.env') !== 'production') {
            $data[Recharge::ORDER_NO] = date('YmdHis') . mt_rand(1000, 9999);
        }

        return $data;
    }
}
