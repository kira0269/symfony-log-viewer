# LogViewer for Symfony 5.x
## Installation

- composer require kira0269/symfony-log-viewer-bundle

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

    log_pattern: '\[<date>\] <channel>\.<level>: <message> <context> <extra>'

    groups:
        date:
            regex: '.*'
            type: date
        channel:
            regex: '[a-z_]+'
            type: text
        level:
            regex: '[A-Z]+'
            type: text
        message:
            regex: '.*'
        context:
            regex: (?'array1'\[(?>(?>[^[\]]+)|(?&array1))*\])|(?'object1'{(?>(?>[^{}]+)|(?&object1))*}) # match array1 or object1
            type: json
        extra:
            regex: (?'array2'\[(?>(?>[^[\]]+)|(?&array2))*\]) # match array2
            type: json
            
    dashboard:
        date: 'yesterday'
        metrics_per_row: 3
        metrics:
            logs_of_day:
                title: Daily
                type: counter
                color: blue-600
                icon: fa-calendar-check
                filters:
                    level: [ INFO ]
                    channel: [ 'security' ]
                    message: [ 'login' ]
            notice:
                title: Notice
                color: yellow-600
                icon: fa-calendar-check
                filters:
                    level: [ NOTICE ]
            info:
                title: Info
                color: green-600
                icon: fa-calendar-check
                filters:
                    level: [ INFO ]
            debug:
                title: Debug
                color: gray-600
                icon: fa-calendar-check
                filters:
                    level: [ DEBUG ]
            error:
                title: Error
                color: red-600
                icon: fa-calendar-check
                filters:
                    level: [ ERROR ]
            all_logs:
                title: All
                color: indigo-600
                icon: fa-calendar-check
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
Define how many metrics should be displayed per row
```yaml
metrics_per_row: integer
```

Define dashboard date filter
```yaml
date: string (`today` or `yesterday` or `-6 day`)
```

Add custom blocks with parameters :

```yaml
title: title of your block
type: counter by default
icon: fontawesome icon
color: tailwind css color of the block title and figure
filters: array of filters depending on groups names
```

Filters examples :

```yaml
level: array of severity levels (case sensitive)
date: array of dates, ie. `today` or `yesterday` or `-6 day`
channel: array ie. `security` or 'authentication`
message: array of regexes that match the log message
extra: array of regexes that match the log extra message
```
