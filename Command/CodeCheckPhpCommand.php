<?php

namespace Rnijveld\Sf2CodeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Rnijveld\Sf2CodeBundle\CodeSniffer\CLI;

class CodeCheckPhpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('code:check:php')
            ->setDescription('Check the src directory for code style issues')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $phpcs = new CLI();
        $phpcs->setInput($input);
        $phpcs->setContainer($this->getContainer());
        $phpcs->checkRequirements();

        $numErrors = $phpcs->process();
        if ($numErrors === 0) {
            exit(0);
        } else {
            exit(1);
        }
    }
}
