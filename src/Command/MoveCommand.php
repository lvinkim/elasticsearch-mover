<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 3/28/18
 * Time: 1:33 PM
 */

namespace App\Command;

use App\Service\IndexMover;
use App\Service\MappingMover;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MoveCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:move')
            ->addOption('from-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('from-type', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-host', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-index', null, InputOption::VALUE_REQUIRED)
            ->addOption('to-type', null, InputOption::VALUE_REQUIRED)
            ->addOption('action', null, InputOption::VALUE_OPTIONAL, '', 'index')
            ->setDescription('索引数据迁移');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fromHost = $input->getOption('from-host');
        $fromIndex = $input->getOption('from-index');
        $fromType = $input->getOption('from-type');
        $toHost = $input->getOption('to-host');
        $toIndex = $input->getOption('to-index');
        $toType = $input->getOption('to-type');
        $action = $input->getOption('action');

        if ('index' === $action) {
            IndexMover::move($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType);
        } elseif ('mapping' === $action) {
            MappingMover::move($fromHost, $fromIndex, $fromType, $toHost, $toIndex, $toType);
        } else {
            $output->writeln("error action '{$action}'");
        }

        $output->writeln('done');
    }

}