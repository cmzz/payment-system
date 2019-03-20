<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\App;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AppGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建一个app';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->ask('请输入用户ID');
        $title = $this->ask('请输入应用名称');
        $description = $this->ask('请输入应用介绍');
        $callbackUrl = $this->ask('请输入应用同步回调地址');
        $notifyUrl = $this->ask('请输入应用异步通知地址');

        if (!$userId || !$title || !$callbackUrl || !$notifyUrl) {
            $this->error('输入错误');
        }

        App::create([
            App::USER_ID => $userId,
            App::APP_KEY => mt_rand(1000000, '9999999'),
            App::APP_SECRET => Str::random(32),
            App::TITLE => $title,
            App::DESCRIPTION => $description,
            App::CALLBACK_URL => $callbackUrl,
            App::NOTIFY_URL => $notifyUrl,
        ]);

        $this->line('Done!');
    }
}
