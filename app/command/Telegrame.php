<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @purpose 测试飞机号
 * @link https://core.telegram.org/API
 * 想开一台吃灰机器，没有IP故无法开机，使用脚本刷机，并推送到Telegrame。其实刷机就可以了，推不推送也没多大关系。但脚本需要填写这些参数，不会修改就照抄了。
 * 一、加入 BotFather 机器人
 * 点击网址https://t.me/BotFather ，打开与它的聊天界面。
 *
 * 二、创建 bot 并获取 token
 * 2.1 创建机器人
 * 输入 /newbot 回车
 * 显示：Alright, a new bot. How are we going to call it? Please choose a name for your bot.
 *
 * 2.2 输入机器人的名称
 * 比如输入 winamp bot 回车
 * 显示：Good. Now let’s choose a username for your bot. It must end in bot. Like this, for example: TetrisBot or tetris_bot.
 *
 * 2.3 输入唯一的机器人用户名
 * 格式为winamp_bot 或 winampbot 必须以bot结尾。
 * 失败后显示：Sorry, xxxxxxxxxx
 * 成功后显示：
 * Done! Congratulations on your new bot. You will find it at t.me/winamp_bot. You can now add a description, about section and profile picture for your bot, see /help for a list of commands. By the way, when you’ve finished creating your cool bot, ping our Bot Support if you want a better username for it. Just make sure the bot is fully operational before you do this.
 *
 * Use this token to access the HTTP API:
 * 5538174337:AAHCLfyReMOfeLnp_znYWsICjTd1e3ApRGg
 * Keep your token secure and store it safely, it can be used by anyone to control your bot.
 *
 * 2.4 提取token
 * 2.3中HTTP API 下面一行就是需要的token。
 *
 * 三、获取chat_id
 * 3.1先测试一下
 * 浏览器中输入：https://api.telegram.org/bot{token}/getUpdates 回车
 * 其中：{token}为2.4中获取的token，包括大括号。
 * 显示：{
 * “ok”: true,
 * “result”: []
 * }
 * 如果显示error，说明有错误。
 *
 * 3.2 获取chat_id
 * 3.2.1 在你生成的机器人中（本例为winamp bot的机器人）随意输入一个词语，比如“Hello World”。
 * 3.2.2 重新在浏览器中输入https://api.telegram.org/bot{token}/getUpdates
 * 其中：{token}为2.4中获取的token，包括大括号。
 * 3.2.3 在显示的ok页中找到”chat”: {“id”: 123456789，”first_name”…….其中id后的数字即为需要的chat_id。
 *
 * 3.3 curl 测试一下获取到的taken和chat_id
 * 在vps中输入命令
 *
 * curl -s -X POST https://api.telegram.org/bot{token}/sendMessage -d chat_id={chatId} -d text="Hello World"
 * 其中：{token}、{chatId}分别为获取的token和chatid，包括大括号。
 *
 * 3.4 成功与否
 * Telegrame客户端中的winamp bot收到”Hello World”，就成功了！
 *
 * 创建机器人方法
 * 1. 创建机器人并获取 Bot Token
 * 如果还没有机器人，需要先创建一个。以下是如何通过 BotFather 创建一个 Telegram 机器人并获取 Bot Token 的步骤：
 *
 * 打开 Telegram 应用:
 * 使用手机或桌面应用打开 Telegram。
 *
 * 与 BotFather 交互:
 * 在搜索栏中搜索 BotFather，这是创建和管理 Telegram 机器人的官方机器人。
 *
 * 创建新机器人:
 * 与 BotFather 开始聊天，然后发送命令 /newbot。
 *
 * 提供机器人名称:
 * BotFather 会提示你输入新机器人的名称。这个名称可以是你喜欢的任何名字。
 *
 * 提供用户名:
 * 接下来，BotFather 会要求你为你的机器人选择一个用户名。这个用户名必须以 "bot" 结尾，例如 my_test_bot。
 *
 * 获取 Bot Token:
 * BotFather 会生成并提供你的机器人令牌。这个令牌看起来像这样：
 *
 * 2. 向机器人发送消息
 * 一旦你创建了机器人并获得了 Bot Token，可以按照以下步骤与机器人聊天：
 *
 * 找到你的机器人:
 * 在 Telegram 搜索栏中输入你的机器人用户名（例如 @my_test_bot），然后点击进入聊天。
 *
 * 启动与机器人的对话:
 * 发送 /start 命令来启动与机器人的对话。这样机器人就可以开始接收你的消息了。
 *
 */
class Telegrame extends Command
{
    protected static $defaultName = 'message';
    protected static $defaultDescription = 'Telegrame';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $bot_token = '7339362087:AAGIWrec4iyNMmvVMc5Bbz8-FUZo8H8VHJk';

        $bot_username = "yanglong_bot";

        $telegram = new \zafarjonovich\Telegram\BotApi($bot_token);

        //$chat_id = 6501097796;
        $chat_id = -4247452277;
        /** 测试群 */
        $chat_id = -4239713236;
        $text = '测试群';
        $telegram->sendMessage($chat_id, $text);
        $updates = $telegram->getUpdates();
        foreach ($updates['result'] as $update){
            if (isset($update['message'])){
                $chat_id = $update['message']['chat']['id'];
                $text = $update['message']['text']??'';
                $message_type = $update['message']['chat']['type'];  // 群组类型（group, supergroup, channel）

                // 打印群组消息和 chat_id
                echo "Chat ID: " . $chat_id . "\n";
                echo "Message: " . $text . "\n";
                echo "Message Type: " . $message_type . "\n";
            }
        }




        return 1;
    }

}
