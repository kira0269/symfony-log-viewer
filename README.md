# LogViewer for Symfony 5.x
## Installation

- composer require kira0269/symfony-log-viewer

## Configuration
Add these lines in ``config/routes/kira_log_viewer.yaml`` :

```yaml 
kira_log_viewer:
    resource: "@LogViewerBundle/Resources/config/routes.yaml"
    prefix:   /logs
```

And these lines in ``config/packages/kira_log_viewer.yaml`` :
```yaml 
kira_log_viewer:
    logs_dir: '%kernel.logs_dir%/rec'
    file_pattern:
        date_format: 'Y-m-d'
    parsing_rules:
        regex: '\[<date>\] <category>\.<severity>: <log>'
        group_regexes:
            date: '.*'
            category: '[a-z_]+'
            severity: '[A-Z]+'
            log: '.*'
```

## Usage
Inject the LogParserInterface into your Controller/Service and parse your logs :
```php
class DefaultController extends AbstractController
{

    public function logsOfTheDay(LogParserInterface $logParser): Response
    {
        $date = new DateTime('now');
        $logs = $logParser->parseLogs($date);
        ...
    }

}
```
