# Laravel SED  

This is a CLI utility that helps in the aid of replacing values or expressions in laravel config files.
  
> **Note:**  This project is primarily intended as CLI tool to manage laravel applications from the outside, although it contains several laravel commands expect that the majority of features will be unavailable as Laravel commands.

## Installation
```bash
composer global require oscarnevarezleal/laravel-sed
```

## Usage
```bash
Larased

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

## Examples  
Change a literal value, e.g change _faker_locale_ to _es_MX_
  
```bash  
larased larased:config-edit faker_locale es_MX  
```

Modify by array path
```bash 
larased larased:config-edit config.database/connections.mysql.username noroot
```
Modify by array path with environment variable check first
```bash
larased -a config.edit -e DB_USERNAME -p connections.mysql.username eb_user
```

## Commands

| Command        |Description                          |Laravel Example                         |
|----------------|-------------------------------|-----------------------------|
|larased:config-edit | Replace literal value in configuration            |`config.php/`_`timezone`_|

## Options
|                |ASCII                          |                         |
|----------------|-------------------------------|-----------------------------|
|-e --envor        |`EnvOr`            | When this option is specified, the result will be a call to _env_ function using primarily the value taken from the environment key specified under the `key|-k`  parameter and secondarily a default value specified under the `value|-v` parameters. Example: `'env' => env('APP_ENV', 'production')`|

### Docker
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
