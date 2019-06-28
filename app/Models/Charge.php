<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Charge extends Model
{
    protected $table = 'charges';

    const ID = 'id';
    const APP_ID = 'app_id';
    const USER_ID = 'user_id';
    const BUYER_ID = 'buyer_id';
    const BUYER_OPENID = 'buyer_openid';
    const ORDER_NO = 'order_no';
    const CHARGE_NO = 'charge_no';
    const CLIENT_IP = 'client_ip';
    const SUBJECT = 'subject';
    const BODY = 'body';
    const EXTRA = 'extra';
    const CHANNEL = 'channel';
    const PAYMENT_PLATFORM = 'payment_platform';
    const STATUS = 'status';
    const REFUND_STATUS = 'refund_status';
    const REFUND_REASON = 'refund_reason';
    const PAID = 'paid';
    const REFUNDED = 'refunded';
    const REVERSED = 'reversed';
    const PAY_AT = 'pay_at';
    const TIME_EXPIRE = 'time_expire';
    const TIME_SETTLE = 'time_settle';
    const TRANSACTION_NO = 'transaction_no';
    const TRANSACTION_ORG_DATA = 'transaction_org_data';
    const AMOUNT = 'amount';
    const AMOUNT_SETTLE = 'amount_settle';
    const CURRENCY = 'currency';
    const FEE_RATE = 'fee_rate';
    const FEE = 'fee';
    const DELETED_AT = 'deleted_at';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const PREPAY_ID = 'prepay_id';
    const NONCE_STR = 'nonce_str';

    protected $fillable = [
        self::ID,
        self::APP_ID,
        self::USER_ID,
        self::BUYER_ID,
        self::BUYER_OPENID,
        self::CHARGE_NO,
        self::ORDER_NO,
        self::CLIENT_IP,
        self::SUBJECT,
        self::BODY,
        self::EXTRA,
        self::CHANNEL,
        self::PAYMENT_PLATFORM,
        self::STATUS,
        self::REFUND_STATUS,
        self::REFUND_REASON,
        self::PAID,
        self::REFUNDED,
        self::REVERSED,
        self::PAY_AT,
        self::TIME_EXPIRE,
        self::TIME_SETTLE,
        self::TRANSACTION_NO,
        self::TRANSACTION_ORG_DATA,
        self::AMOUNT,
        self::AMOUNT_SETTLE,
        self::CURRENCY,
        self::FEE_RATE,
        self::FEE,
        self::DELETED_AT,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::PREPAY_ID,
        self::NONCE_STR,
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function getYuanAmount(): float
    {
        return $this->attributes[self::AMOUNT] * 0.01;
    }

    public function isAlipay(): bool
    {
        return Str::startsWith(strtolower($this->attributes[self::CHANNEL]), 'alipay_');
    }

    public function isWechatPay(): bool
    {
        return Str::startsWith(strtolower($this->attributes[self::CHANNEL]), 'wechatpay_');
    }

    public function isQpay(): bool
    {
        return Str::startsWith(strtolower($this->attributes[self::CHANNEL]), 'qpay_');
    }

    public function buildNotifyData(): array
    {
        return [
            self::AMOUNT => $this->attributes[self::AMOUNT],
            self::STATUS => $this->attributes[self::STATUS],
            self::PAID => $this->attributes[self::PAID],
            self::ORDER_NO => $this->attributes[self::ORDER_NO],
            self::TRANSACTION_NO => $this->attributes[self::TRANSACTION_NO],
            self::PAY_AT => $this->attributes[self::PAY_AT],
            self::BUYER_ID => $this->attributes[self::BUYER_ID],
            self::BUYER_OPENID => $this->attributes[self::BUYER_OPENID],
            'app_key' => $this->app->{App::APP_KEY}
        ];
    }
}
