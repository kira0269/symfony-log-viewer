<?php


namespace Kira0269\LogViewerBundle\Services\LogParser;


use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LogParser implements LogParserInterface
{
    private string $logsDir;
    private array $parsingRules;
    private array $errors = [];

    /**
     * LogParser constructor.
     *
     * @param string $logsDir - Logs directory.
     * @param array  $parsingRules - Parsing rules defined in config.
     */
    public function __construct(string $logsDir, array $parsingRules)
    {
        $this->logsDir = $logsDir;
        $this->parsingRules = $parsingRules;
    }

    /**
     * Return true if the log parser has errors.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return [] !== $this->errors;
    }

    /**
     * Parse all .log files in logs directory
     * and return them in an array.
     *
     * @return array
     */
    public function parseLogs(): array
    {
        $parsedLogs = [];
        $this->errors = [];

        $finder = new Finder();
        $fileIterator = $finder
            ->in($this->logsDir)
            ->files()
            ->name('*.log')
            ->getIterator();

        foreach ($fileIterator as $fileInfo) {
            $parsedFile = $this->parseLogFile($fileInfo);

            if (!empty($parsedFile)) {
                $parsedLogs[$fileInfo->getFilename()] = $this->parseLogFile($fileInfo);
            }
        }

        return $parsedLogs;
    }

    /**
     * Parse the content of a file
     * and return it in an array.
     *
     * @param SplFileInfo $logFile
     *
     * @return array
     */
    private function parseLogFile(SplFileInfo $logFile): array
    {
        $parsedFile = [];

        $content = $logFile->getContents();
        $lines = explode("\n", $content);

        foreach ($lines as $lineNumber => $line) {

            try {
                $parsedLine = $this->parseLine($line);

                if (!empty($parsedLine)) {
                    $parsedFile[] = $parsedLine;
                }

            } catch (\Exception $exception) {
                $this->errors[] = [
                    'log_file' => $logFile->getRealPath(),
                    'line' => $line,
                    'error' => $exception->getMessage()
                ];
            }

        }

        return $parsedFile;
    }

    /**
     * Parse a log string with regex.
     *
     * @param string $lineToParse
     *
     * @return array
     * @throws \Exception
     */
    private function parseLine(string $lineToParse): array
    {
        $parsedLine = [];

        $success = preg_match('/^'.$this->parsingRules['regex'].'$/', $lineToParse, $parsedLine);

        if (false === $success) {
            throw new \Exception('Error during log parsing !');
        }

        // If 'group_regexes' is configured, we keep only groups defined in it.
        if (!empty($this->parsingRules['group_regexes'])) {
            foreach ($parsedLine as $key => $match) {
                if (!key_exists($key, $this->parsingRules['group_regexes'])) {
                    unset($parsedLine[$key]);
                }
            }
        }

        return $parsedLine;
    }
}