<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 20.11.14
 * Time: 16:07
 */
namespace Csv\Mvc\View;

use Csv\Csv;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class CsvRenderingStrategy extends AbstractListenerAggregate
{

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'selectCsvRenderer'));
    }

    public function selectCsvRenderer(MvcEvent $e)
    {
        $csv = $e->getResult();
        if (!$csv instanceof Csv) {
            return;
        }

        $filename = $csv->getFileName() == null ? uniqid() : $csv->getFileName();
        $response = $e
            ->getResponse()
            ->setContent((string) $csv);

        $response
            ->getHeaders()
            ->addHeaderLine('content-type', 'application/x-msdownload')
            ->addHeaderLine('content-disposition', 'attachment; filename=' . $filename . '.csv')
            ->addHeaderLine('pragma', 'no-cache');

        $e->setResult($response);
    }
}