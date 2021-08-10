<?php


namespace Kira0269\LogViewerBundle\LogParser;


interface LogParserInterface
{
    public function __construct(string $logsDir, array $filePattern, string $logPattern, array $groupsConfig);

    public function parseLogs(\DateTime $dateTime, string $filePattern = null, bool $merge = false): array;

    public function getErrors(): array;
}
