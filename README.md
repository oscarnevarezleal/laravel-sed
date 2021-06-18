# Laravel SED  

This is a CLI utility that helps in the aid of replacing values or expressions in laravel config files.
  
> **Note:**  This project is primarily intended as CLI tool to manage laravel applications from the outside, although it contains several laravel commands expect that the majority of features will be unavailable as Laravel commands.

## Installation
```bash
composer global require oscarnevarezleal/laravel-sed
```

## Usage
```bash
Larased 0.0.3

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help                  Displays help for a command
  list                  Lists commands
 larased
  larased:config-edit   Edits a config file
```

### Commands

| Command        |Description                          |Laravel Example                         |
|----------------|-------------------------------|-----------------------------|
|larased:config-edit | Replace literal value in configuration            |`config.php/`_`timezone`_|

### Options
|                |ASCII                          |                         |
|----------------|-------------------------------|-----------------------------|
|-e --envor        |`EnvOr`            | When this option is specified, the result will be a call to _env_ function using primarily the value taken from the environment key specified under the `key|-k`  parameter and secondarily a default value specified under the `value|-v` parameters. Example: `'env' => env('APP_ENV', 'production')`|

## Examples

### Literal values
Take the following part of the configuration file `config/app.php` for instance.

```php
# config/app.php
<?php

return [
    'name' => 'App',
    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */
    'debug' => (bool) env('APP_DEBUG', false),
    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL', null)
    
    #....
];

```

If you'd like to change a literal value, e.g., _name_ to _MyAwesomeApp_ you run the following command:
  
```bash  
larased larased:config-edit config.app/name my-awesome-app  
```

It will result in:
```php
<?php

return [
    'name' => 'my-awesome-app',
```
What about environment values check with a default value? In that case we need to use the `-e` flag like this:

```bash  
larased larased:config-edit config.app/name -e APP_NAME my-awesome-app  
```
It will result in:
```php
<?php

return [
    'name' => env('APP_NAME', 'MyAwesomeApp'),
```

### Nested properties

Some other properties are not very at hand but nested under an array path. Take the username of a mysql connection for instance.

```bash 
larased larased:config-edit config.database/connections.mysql.username noroot
```
It will result on a successful update as shown below.
```php
# ...
	'connections' => [
		'sqlite' => [
			'driver' => 'sqlite',
			'url' => env('DATABASE_URL'),
			'database' => env('DB_DATABASE', database_path('database.sqlite')),
			'prefix' => '',
			'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
		],
		'mysql' => [
			'driver' => 'mysql',
			'url' => env('DATABASE_URL'),
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => 'no-root',

# ...

```

### Environment chain with default

Sometimes there's no one but multiple environment variables you'd like to use before the default. In that case the flag `-e` has a convenient variant that we can use. A list of environment variables names can be separated by a pipe `|`. 

```bash
larased larased:config-edit config.database/connections.mysql.username noroot -e "DB_USER|DB_USER_ENV"
```

## Docker
```bash
# Pull latest
docker pull docker pull docker.pkg.github.com/oscarnevarezleal/laravel-sed/laravel-sed:dev

# create an alias
alias larased='docker run --rm -it -v `pwd`:/var/laraseed:ro laravel-sed:latest'
```

```bash
#windows
docker run --rm -it -v ${PWD}:/var/laraseed:ro laravel-sed:latest config.edit faker_locale es_MX  

#linux and MacOS
docker run --rm -it -v `pwd`:/var/laraseed:ro laravel-sed:latest config.edit faker_locale es_MX  
```

## Known Drawbacks

### How to Apply Coding Standards?

This package uses [nikic/php-parser](https://github.com/nikic/PHP-Parser/), built on technology called an *abstract syntax tree* (AST). An AST doesn't know about spaces and when written to a file it produces poorly formatted code in both PHP and docblock annotations. **That's why your project needs to have a coding standard tool** such as [ECS](https://github.com/symplify/easy-coding-standard).