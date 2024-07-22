<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @purpose 投递消息到队列
 * @command php webman publish
 */
class Publish extends Command
{
    protected static $defaultName = 'Publish';
    protected static $defaultDescription = '测试投递消息示例';

    /**
     * @return void
     */
    protected function configure()
    {
        //$this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * 业务逻辑
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        DemoJob::publish(['name'=>'tome','age'=>15]);
        $output->writeln('投递消费者完成');
        return self::SUCCESS;
    }

}
