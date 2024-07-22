<?php

namespace app\command;

use Xiaosongshu\Rabbitmq\Client;

/**
 * @purpose 定义处理业务逻辑任务
 */
class DemoJob extends Client
{

    /** 以下是rabbitmq配置 ，请填写您自己的配置 */
    /** @var string $host 服务器地址 */
    public static $host = "127.0.0.1";
    /** @var int $port 服务器端口 */
    public static $port = 5672;
    /** @var string $user 服务器登陆用户 */
    public static $user = "guest";
    /** @var string $pass 服务器登陆密码 */
    public static $pass = "guest";
    /**
     * 业务处理
     * @param array $params
     * @return int
     */
    public static function handle(array $params): int {
        /** 假设这里处理一堆业务，巴拉巴拉 */
        var_dump($params);
        return self::ACK;
    }
}
