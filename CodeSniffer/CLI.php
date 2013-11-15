<?php

namespace Rnijveld\Sf2CodeBundle\CodeSniffer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Rnijveld\Sf2CodeBundle\CodeSniffer\Standards\Symfony2\Standard;

class CLI extends \PHP_CodeSniffer_CLI implements ContainerAwareInterface
{
    /**
     * @var InputInterface|null
     */
    protected $input;

    /**
     * @var ContainterInterface|null
     */
    protected $container;

    /**
     * @param InputInterface $input
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getCommandLineValues()
    {
        if (defined('PHP_CODESNIFFER_IN_TESTS') === true) {
            return array();
        }

        if (empty($this->values) === false) {
            return $this->values;
        }

        $container = $this->container;

        $values = $this->getDefaults();
        $values['standard'] = Standard::getDirectory();
        $values['files'][] = realpath($container->getParameter('kernel.root_dir') . '/../src/');
        $values['showProgress'] = true;
        $values['encoding'] = 'UTF-8';
        $values['tabWidth'] = 4;

        $this->values = $values;
        return $values;
    }
}
