<?php

namespace App\Lib;

class RedisSession implements \SessionHandlerInterface
{
    private $redis;

    public function __construct()
    {
        $this->redis = app('redis');
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $this->redis->del('session:'.$session_id);
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $session_id)
    {
        return true;
    }

    public function read($session_id)
    {
        $result = $this->redis->get('session:'.$session_id);
        if (!$result) {
            $result = '';
        }
        return $result;
    }

    public function write($session_id, $session_data, $expire_time = 3600*24)
    {
        $this->redis->set('session:'.$session_id, $session_data);
        $this->redis->expire('session:'.$session_id, $expire_time);
        return true;
    }
}