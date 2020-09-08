# Laravel SED  

This is a CLI utility that helps in the aid of replacing values or expressions in laravel config files.

> **Note:**  Not every possible edit is possible right now mainly nested properties such as `database.connections.mysql.*` or `filesystems.disks.local.driver.*`
  
> **Note:**  This project is primarily intended as CLI tool to manage laravel applications from the outside, although it contains several laravel commands expect that the mayority of features will be unavailable as Laravel commands.

## Getting started

### Bin alias
```bash
# create an alias
alias larased='php cli/php/main.php '
```


### Docker alias
```bash
# create an alias
alias larased='docker run --rm -it -v `pwd`:/var/laraseed:ro laravel-sed:latest '

#windows
docker run --rm -it -v ${PWD}:/var/laraseed:ro laravel-sed:latest -a config.edit -p faker_locale -v es_MX  
#linux MacOS
docker run --rm -it -v `pwd`:/var/laraseed:ro laravel-sed:latest -a config.edit -p faker_locale -v es_MX  
```

## Usage  
  
```bash  
# How to change a literal value, e.g change faker_locale to es_MX
larased -a config.edit -p faker_locale -v es_MX  

# Add a provider
larased -a config.append_array_value -p providers -v App\CustomProvider

# Append array association to path, e.g aliases
# This will append 'MyKey' => App\CustomProvider::class to aliases
larased -a config.append_array_class_assoc -p aliases -v App\CustomProvider -k MyKey
  
```

## Commands

| Command        |Description                          |Laravel Example                         |
|----------------|-------------------------------|-----------------------------|
|config.edit | Replace literal value in configuration            |`config.php/`_`timezone`_|
|config.append_array_value | Adds a new array item to the array  |`config.php/`_`providers`_|
|config.append_array_class_assoc | Adds a new array association to the array  |`config.php/`_`aliases`_|
|config-gen globals | Generates a config file from arguments




## Options
|                |ASCII                          |                         |
|----------------|-------------------------------|-----------------------------|
|-a --action     |`Action`            | The action or _command_ to run. See commands for reference |
|-p --path       |`Path`            | The path where the modification will take place. Example `faker_locale`  or `providers` |
|-k --key        |`Key`            | The key to modify in case the modification will need to look up into the path. |
|-e --envor        |`EnvOr`            | When this option is specified, the result will be a call to _env_ function using primarily the value taken from the environment key specified under the `key|-k`  parameter and secondarily a default value specified under the `value|-v` parameters. Example: `'env' => env('APP_ENV', 'production')`|
|-v --value      |`Value`            | The new value|




## Local
If you want to run this project locally you'll need a laravel application located in the app folder.
```bash
composer create-project --prefer-dist laravel/laravel app  
```
