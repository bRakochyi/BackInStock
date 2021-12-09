<?php
/**
 * Elogic Cron
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Cron;

use Elogic\BackInStock\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * BackInStock cron class
 *
 * @package Elogic\BackInStock\Cron
 */
class BackInStock
{
    /**
     * Property for LoggerInterface
     *
     * @param LoggerInterface $logger
     */
    protected $logger;

    /**
     * Property for Data
     *
     * @param Data $helper
     */
    protected $helper;

    /**
     * BackInStock Cron Constructor
     *
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        LoggerInterface $logger,
        Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->addInfo("Cronjob Back In Stock is executed.");
        $this->helper->notifyInterests();
        $this->logger->addInfo("Cronjob Back In Stock is finished.");
    }
}
