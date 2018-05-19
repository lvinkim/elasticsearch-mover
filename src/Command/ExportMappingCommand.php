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

class ExportMappingCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:export:mapping')
            ->addOption('host', null, InputOption::VALUE_REQUIRED)
            ->addOption('index', null, InputOption::VALUE_REQUIRED)
            ->addOption('type', null, InputOption::VALUE_REQUIRED)
            ->addOption('output', null, InputOption::VALUE_REQUIRED)
            ->setDescription('导出索引的 mapping 配置');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $host = $input->getOption('host');
        $index = $input->getOption('index');
        $type = $input->getOption('type');
        $outputFile = $input->getOption('output');

        MappingCopier::export($host, $index, $type, $outputFile);

        $output->writeln('done');
    }

}