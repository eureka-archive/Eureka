# EurekaTemplate
Base "template" for an application with Eureka Framework


## Install (DEV)
To initialize you app, juste perform the following command:
```bash
composer install
```

## Web application
You now can access to your web application according to you server configuration.

## Mains config files
 - `app.yml`: Basic application configuration
 - `cache.yml`: Basic configuration about cache
 - `database.yml`: Configuration to manage connection to you database
 - `error.yml`: Basic configuration about logging / displaying errors
 - `menu.yml`: Menu configuration
 - `meta.yml`: Html & site configuration
 - `middleware.yml`: List of middleware loaded & used in the given order.
 - `package.yml`: List of packages loaded & used in the given order.
 - `services.yml`: List of services loaded & used (like symfony).
 - `theme.yml`: Configuration about theming (css / js files, theme name if applicable)
 - `twig.yml`: Configuration about twig.

 - `media.yml`: Configuration about media files

  Config from those file can be overridden by package configuration files.
  It can be also completed, enriched, updated, changed to corresponding to your needs.

## Intall (PREPROD / PROD)
This template provide a bash & php install scripts to automatize installation in (pre-)production, with
support of non blocking upgrade & easy rollback.
Those script are not documented, it is just provided for example.
If you would use those scripts, you'll need to updated them corresponding to your
server configurations / paths.
