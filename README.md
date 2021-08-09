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
    #logs_dir: '%kernel.logs_dir%' - You can uncommented and edit this line to search logs somewhere else.
    file_pattern:
        date_format: 'Y-m-d'
        
    log_pattern: '\[<date>\] <category>\.<severity>: <log> <context> \[\]'
    groups:
        date:
            regex: '.*'
            type: date
        category:
            regex: '[a-z_]+'
        severity:
            regex: '[A-Z]+'
        log:
            regex: '[^\[.]*'
        context:
            regex: '\[.+\]'
            type: json
            
    dashboard:
        blocks:
            -
                title: Daily
                color: blue
                icon: fa-calendar-check
                filter:
                    severity: [error, notice]
                    date: [today]
                    category: [security]
                    log: ['^MAIL$']
            -
                title: Notice
                color: yellow
                icon: fa-calendar-check
                filter:
                    severity: [notice]
            -
                title: Info
                color: green
                icon: fa-calendar-check
                filter:
                    severity: [info]
            -
                title: Debug
                color: gray
                icon: fa-calendar-check
                filter:
                    severity: [debug]
            -
                title: Error
                color: red
                icon: fa-calendar-check
                filter:
                    severity: [error]
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

## Dashboard configuration
Add custom blocks with parameters :

```yaml
title: title of your block
icon: fontawesome icon
color: tailwind css color of the block title and figure
filter: array of filters depending on parsing_rules
```

Filters examples :

```yaml
severity: array of severity levels
date: array of dates, ie. `today` or `yesterday` or `-6 day`
category: array ie. `security` or 'authentication`
log: array of regexes that match the error message
```
