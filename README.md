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
            regex: (?'array1'\[(?>(?>[^[\]]+)|(?&array1))*\]) # match array1
            type: json
        extra:
            regex: (?'array2'\[(?>(?>[^[\]]+)|(?&array2))*\]) # match array2
            type: json
            
    dashboard:
        blocks:
            -
                title: Daily
                color: blue
                icon: fa-calendar-check
                filter:
                    level: [error, notice]
                    date: [today]
                    channel: [security]
                    message: ['^MAIL$']
            -
                title: Notice
                color: yellow
                icon: fa-calendar-check
                filter:
                    level: [notice]
            -
                title: Info
                color: green
                icon: fa-calendar-check
                filter:
                    level: [info]
            -
                title: Debug
                color: gray
                icon: fa-calendar-check
                filter:
                    level: [debug]
            -
                title: Error
                color: red
                icon: fa-calendar-check
                filter:
                    level: [error]
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
filter: array of filters depending on groups names
```

Filters examples :

```yaml
level: array of severity levels
date: array of dates, ie. `today` or `yesterday` or `-6 day`
channel: array ie. `security` or 'authentication`
message: array of regexes that match the log message
```
