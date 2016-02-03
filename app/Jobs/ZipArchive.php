<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Order\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ZipArchive extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order;
    protected $extractDir;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $extractDir)
    {
        //
        $this->order = $order;
        $this->extractDir = $extractDir;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //执行解压缩文件
        //获取文件位置
        $zip = new \ZipArchive;
        if ($zip->open(public_path($this->order->id_image)) === TRUE) {
            $zip->extractTo(public_path($this->extractDir));
            $zip->close();

        } else {

        }
    }
}
