<?php


namespace Kira0269\LogViewerBundle\LogParser;


use DateTime;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LogParser implements LogParserInterface
{
    private string $logsDir;
    private array $filePattern;
    private array $parsingRules;

    private array $errors = [];

    /**
     * LogParser constructor.
     *
     * @param string $logsDir      - Logs directory.
     * @param array  $filePattern  - Log filenames pattern.
     * @param array  $parsingRules - Parsing rules defined in config.
     */
    public function __construct(string $logsDir, array $filePattern, array $parsingRules)
    {
        $this->logsDir = $logsDir;
        $this->filePattern = $filePattern;
        $this->parsingRules = $parsingRules;
    }

    /**
     * Return errors thrown during parsing.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return true if the log parser has errors.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Parse all .log files for a date in logs directory
     * and return them in an array.
     *
     * @param DateTime $dateTime
     *
     * @param bool     $merge    - If true, merge all logs from several files into one array.
     *
     * @return array
     */
    public function parseLogs(DateTime $dateTime, bool $merge = false): array
    {
        $parsedLogs = [];
        $this->errors = [];

        $formattedDate = $dateTime->format($this->filePattern['date_format']);

        $finder = new Finder();
        $fileIterator = $finder
            ->in($this->logsDir)
            ->files()
            ->name("*$formattedDate.log")
            ->getIterator();

        foreach ($fileIterator as $fileInfo) {
            $parsedFile = $this->parseLogFile($fileInfo);

            if (!empty($parsedFile)) {
                $parsedLogs[$fileInfo->getFilename()] = $parsedFile;
            }
        }

        if ($merge) {
            // Unpacking string-keys arrays is possible since PHP 8.1
            if (PHP_MAJOR_VERSION >= 8 && PHP_MINOR_VERSION >= 1) {
                $parsedLogs = array_merge([], ...$parsedLogs);
            } else {
                $parsedLogs = array_merge([], ...array_values($parsedLogs));
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

        $file = new \SplFileObject($logFile->getRealPath());

        // Loop until we reach the end of the file.
        while (!$file->eof()) {
            try {
                $parsedLine = $this->parseLine($file->fgets());

                if (!empty($parsedLine)) {
                    $parsedFile[] = $parsedLine;
                }

            } catch (\Exception $exception) {
                $this->errors[] = [
                    'log_file' => $logFile->getRealPath(),
                    'error' => $exception->getMessage()
                ];
            }
        }

        // Unset the file to call __destruct(), closing the file handle.
        $file = null;

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
