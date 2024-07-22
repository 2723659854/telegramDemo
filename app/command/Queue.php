<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @purpose 定义一个消费者
 * @comment 本命令行是阻塞的，会一直消费数据
 * @command php webman queue
 */
class Queue extends Command
{
    protected static $defaultName = 'Queue';
    protected static $defaultDescription = '一个rabbitmq队列消费者示例';

    /**
     * @return void
     */
    protected function configure()
    {
        //$this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * 业务逻辑处理函数
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("开启消费者");
        /** 开启消费者 */
        DemoJob::consume();
    }

}
