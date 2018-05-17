<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 3/28/18
 * Time: 1:33 PM
 */

namespace App\Command;

use App\Service\IndexCopier;
use App\Service\MappingCopier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportIndexCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:import:index')
            ->setDescription('导入索引数据');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('done');
    }

}