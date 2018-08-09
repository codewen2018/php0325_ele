<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OrderClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //命令的名称
    protected $signature = 'order:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'order clear 11111';

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
    //所有逻辑处理放在这里
    public function handle()
    {
        /**
         * 1.找出 超时   未支付   订单
         * 当前时间-创建时间>15*60
         * 当前时间-15*60>创建时间
         * 创建时间<当前时间-15*60
         * */
        while (true){
            $orders=\App\Models\Order::where("status",0)->where('created_at','<',date("Y-m-d H:i:s",(time()-15*60)))->update(['status'=>-1]);
            if ($orders){
                echo date("Y-m-d H:i:s")." clear ok".PHP_EOL;
            }
            sleep(1);
        }
    }
}
