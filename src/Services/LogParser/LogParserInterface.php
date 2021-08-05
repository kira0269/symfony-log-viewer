<?php


namespace Kira0269\LogViewerBundle\Services\LogParser;


interface LogParserInterface
{
    public function __construct(string $logsDir, array $logFormat);

    public function parseLogs(): array;
}