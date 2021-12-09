<?php
/**
 * Elogic Console Command
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Console\Command;

use Elogic\BackInStock\Helper\Data;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BackInStock command class
 *
 * @package Elogic\BackInStock\Console\Command
 */
class BackInStock extends Command
{
    /**
     * Constant for check argument "check"
     */
    const CHECK_ARGUMENT = 'check';

    /**
     * Property for Logger Interface
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Property for State
     *
     * @var State
     */
    protected $state;

    /**
     * Property for Date time
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Property for Data
     *
     * @var Data
     */
    protected $helper;

    /**
     * Property for Input Interface
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Property for Output Interface
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Construct for BackInStock class
     *
     * @param LoggerInterface $logger
     * @param State $state
     * @param DateTime $dateTime
     * @param Data $helper
     */
    public function __construct(
        LoggerInterface $logger,
        State $state,
        DateTime $dateTime,
        Data $helper
    ) {
        $this->logger = $logger;
        $this->state = $state;
        $this->dateTime = $dateTime;
        $this->helper = $helper;
        parent::__construct();
    }

    /**
     * Execute method for Console
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->state->setAreaCode(Area::AREA_GLOBAL);

        $check = $input->getArgument(self::CHECK_ARGUMENT) ?: false;
        if ($check) {
            $this->output->writeln((string) __('%1 Start Notification Process', $this->dateTime->gmtDate()));
            $this->helper->notifyInterests();
            $this->output->writeln((string) __('%1 End Notification Process', $this->dateTime->gmtDate()));
        }
    }

    /**
     * Configure method for CLI command
     *
     * example: "elogic:backinstock:check [--] <check>"
     */
    protected function configure()
    {
        $this->setName('elogic:backinstock:check');
        $this->setDescription('Notify customers products are back in stock');
        $this->setDefinition([
            new InputArgument(self::CHECK_ARGUMENT, InputArgument::REQUIRED, 'Check')
        ]);
        parent::configure();
    }
}
