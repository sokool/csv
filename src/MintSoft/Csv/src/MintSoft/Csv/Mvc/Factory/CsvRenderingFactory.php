<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 20.11.14
 * Time: 16:14
 */

namespace Csv\Mvc\Factory;

use Csv\Mvc\View\CsvRenderingStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CsvRenderingFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CsvRenderingStrategy;
    }
}