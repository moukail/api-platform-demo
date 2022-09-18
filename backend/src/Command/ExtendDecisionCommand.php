<?php

namespace App\Command;

use App\Repository\DecisionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:decision:extend-active-allowances',
    description: 'Add a short description for your command',
)]
class ExtendDecisionCommand extends Command
{
    private DecisionRepository $repository;

    public function __construct(DecisionRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Extend decisions of active allowances')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $count = $this->repository->extendDecisions();

        $io->success(sprintf('Extended "%d" decisions.', $count));

        return Command::SUCCESS;
    }
}
