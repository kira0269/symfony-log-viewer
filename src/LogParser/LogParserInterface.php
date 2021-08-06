<?php


namespace Kira0269\LogViewerBundle\LogParser;


interface LogParserInterface
{
    public function __construct(string $logsDir, array $filePattern, array $parsingRules);

    public function parseLogs(\DateTime $dateTime, bool $merge = false): array;
}