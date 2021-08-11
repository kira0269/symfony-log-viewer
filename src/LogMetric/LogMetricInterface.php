<?php

namespace Kira0269\LogViewerBundle\LogMetric;

interface LogMetricInterface
{
    public function __construct(array $metricConfig, array $groupsConfig);

    public function calculate(array $logs);

    public function getResult();
}
