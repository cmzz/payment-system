<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewOrderRequest extends FormRequest
{
    const ORDER_NO = 'order_no';
    const AMOUNT = 'amount';
    const CLIENT_IP = 'client_ip';
    const CURRENCY = 'currency';
    const SUBJECT = 'subject';
    const BODY = 'body';

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
            self::SUBJECT => 'required|string|min:2',
        ];
    }

    public function getAmount(): int
    {
        $amount = request()->get(self::AMOUNT, 0);
        if ($amount < 1) {

        }
    }
}
