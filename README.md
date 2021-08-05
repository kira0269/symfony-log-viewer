# symfony-log-viewer

## Installation

- composer require kira0269/symfony-log-viewer
- Add these lines in ``config/routes/kira0269_log_viewer.yaml`` :

```yaml 
kira0269_log_viewer:
    resource: "@LogViewerBundle/Resources/config/routes.yaml"
    prefix:   /logs
```