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

class ExportIndexCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:export:index')
            ->addOption('host', null, InputOption::VALUE_REQUIRED)
            ->addOption('index', null, InputOption::VALUE_REQUIRED)
            ->addOption('type', null, InputOption::VALUE_REQUIRED)
            ->addOption('output', null, InputOption::VALUE_REQUIRED)
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL)
            ->setDescription('导出索引的数据');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host');
        $index = $input->getOption('index');
        $type = $input->getOption('type');
        $limit = $input->getOption('limit');
        $outputFile = $input->getOption('output');

        IndexCopier::export($host, $index, $type, $outputFile, $limit);

        $output->writeln('done');
    }

}