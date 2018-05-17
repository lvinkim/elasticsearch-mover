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

class CopyIndexCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:copy:index')
            ->addOption('from-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-type', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-type', null, InputOption::VALUE_REQUIRED)
            ->addOption('limit', null, InputOption::VALUE_REQUIRED)
            ->setDescription('将一个索引的数据复制到另一个索引中');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fromHost = $input->getOption('from-host');
        $fromIndex = $input->getOption('from-index');
        $fromType = $input->getOption('from-type');
        $toHost = $input->getOption('to-host');
        $toIndex = $input->getOption('to-index');
        $toType = $input->getOption('to-type');

        IndexCopier::copy($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType);

        $output->writeln('done');
    }

}