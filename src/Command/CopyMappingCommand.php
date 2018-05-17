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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CopyMappingCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:copy:mapping')
            ->addOption('from-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-type', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-type', null, InputOption::VALUE_REQUIRED)
            ->setDescription('将一个索引的 mapping 配置更新到另一个索引的 mapping 中');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fromHost = $input->getOption('from-host');
        $fromIndex = $input->getOption('from-index');
        $fromType = $input->getOption('from-type');
        $toHost = $input->getOption('to-host');
        $toIndex = $input->getOption('to-index');
        $toType = $input->getOption('to-type');

        MappingCopier::copy($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType);

        $output->writeln('done');
    }

}