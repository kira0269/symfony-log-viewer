<?php


namespace Kira0269\LogViewerBundle;


use Kira0269\LogViewerBundle\DependencyInjection\LogViewerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LogViewerBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new LogViewerExtension();
        }

        return $this->extension;
    }
}