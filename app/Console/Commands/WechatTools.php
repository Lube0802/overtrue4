<?php

namespace App\Console\Commands;

use EasyWeChat\Factory;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WechatTools extends Command
{
    protected $signature = 'wechat {cmd} {param}';

    protected $description = '微信上传素材工具';

    protected $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger('console');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/wechatUpload.log'), Logger::INFO));
    }

    public function handle()
    {
        $this->logger->info("\ncommand start execute");
        $app = Factory::officialAccount(config('wechat'));
        $cmd = $this->argument('cmd');
        $file = $this->argument('param');

        if (!file_exists($file)) {
            $this->logger->info('File['.$file.'] not exist');
            return;
        }

        $fileInfo = pathinfo($file);

        if ($cmd == 'up') {
            $this->logger->info('upload info: method => '.$cmd.', file => '.$file);

            if (in_array(strtolower($fileInfo['extension']), ['jpg', 'png', 'jpeg', 'gif'])) {
                $result = $app->material->uploadImage($file);
            } elseif (in_array(strtolower($fileInfo['extension']), ['mp3', 'wma', 'wav', 'amr'])) {
                $result = $app->material->uploadVoice($file);
            } elseif (in_array(strtolower($fileInfo['extension']), ['mp4'])) {
                $result = $app->material->uploadVideo($file);
            }
        } elseif ($cmd == 'up2') {
            $this->logger->info('upload info: method => '.$cmd.', file => '.$file);

            if (in_array(strtolower($fileInfo['extension']), ['jpg', 'png', 'jpeg'])) {
                $result = $app->media->uploadImage($file);
            } elseif (in_array(strtolower($fileInfo['extension']), ['mp3', 'amr'])) {
                $result = $app->media->uploadVoice($file);
            } elseif (in_array(strtolower($fileInfo['extension']), ['mp4'])) {
                $result = $app->media->uploadVideo($file);
            }
        } else {
            $this->logger->info('not defined command: '.$cmd);
            return;
        }

        if ($result['media_id']) {
            $this->logger->info('media_id: '.$result['media_id']);
        } else {
            $this->logger->info('upload failed: '.json_encode($result));
        }
    }
}