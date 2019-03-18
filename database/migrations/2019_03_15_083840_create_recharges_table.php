<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharges', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('app_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->string('buyer_id')->nullable()->comment('客户端的买家id');
            $table->string('buyer_openid')->nullable()->comment('客户端的买家openid，微信支付方式时必填');
            $table->string('order_no')->nullable()->comment('客户端系统订单号');
            $table->string('client_ip')->nullable()->comment('发起支付请求客户端的 IP 地址，格式为 IPv4 整型，如 127.0.0.1');
            $table->string('subject')->nullable()->comment('商品名称');
            $table->string('body')->nullable()->comment('商品描述信息，该参数最长为 200 个 Unicode 字符。');
            $table->string('extra')->nullable()->comment('特定渠道发起交易时需要的额外参数，以及部分渠道支付成功返回的额外参数');
            $table->string('channel')->nullable()->comment('支付渠道');
            $table->integer('pay_status')->nullable()->comment('订单的支付状态');
            $table->integer('refund_status')->nullable()->comment('订单的退款状态');
            $table->string('refund_reason', 512)->nullable()->comment('退款理由');
            $table->integer('paid')->nullable()->comment('是否付款');
            $table->integer('refunded')->nullable()->comment('是否存在退款信息，无论退款是否成功。');
            $table->integer('reversed')->nullable()->comment('订单是否撤销。');
            $table->timestamp('pay_at')->nullable()->comment('订单支付完成时的 Unix 时间戳。');
            $table->timestamp('time_expire')->nullable()->comment('订单失效时间的 Unix 时间戳。');
            $table->timestamp('time_settle')->nullable()->comment('订单清算时间，用 Unix 时间戳表示。');
            $table->string('transaction_no')->nullable()->comment('第三方支付系统单号');
            $table->text('transaction_org_data', 65535)->nullable()->comment('第三方支付平台原始数据');
            $table->decimal('amount', 10, 0)->nullable()->comment('订单总金额（必须大于 0），单位为对应币种的最小货币单位，人民币为分。如订单总金额为 1 元，amount 为 100。');
            $table->decimal('amount_settle', 10, 0)->nullable()->comment('清算金额，单位为对应币种的最小货币单位，人民币为分。');
            $table->char('currency', 20)->nullable()->default('cny')->comment('3 位 ISO 货币代码，小写字母，默认人民币为 cny。');
            $table->float('fee_rate', 10, 2)->nullable()->comment('第三方支付平台费率');
            $table->decimal('fee', 10, 2)->nullable()->comment('第三方支付平台扣费');
            $table->string('pre_order_id')->nullable()->comment('支付平台与支付订单号');
            $table->string('once_str')->nullable()->comment('支付平台一次性字符串');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharges');
    }
}
