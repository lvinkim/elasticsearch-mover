<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 3/28/18
 * Time: 1:33 PM
 */

namespace App\Command;

use App\Services\ServerMonitor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:move')
            ->setDescription('索引数据迁移');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('索引数据迁移');
    }

}