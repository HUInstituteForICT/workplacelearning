[![BCH compliance](https://bettercodehub.com/edge/badge/HUInstituteForICT/workplacelearning?branch=master&token=83dd337c2c87d86fa2fe2cde55c50f308c1291d4)](https://bettercodehub.com/)

# WorkplaceLearning

Status: [![Build Status](https://travis-ci.org/HUInstituteForICT/workplacelearning.svg?branch=development)](https://github.com/HUInstituteForICT/workplacelearning/workflows/Verify/badge.svg)

## Getting Started

These instructions will help setup the development environment using docker

### Prerequisites

- Docker
- PHPStorm to make use of pre-configured Xdebug

### Installing

**note:** tools like composer &amp; npm are used in the PHP container. If these tools are available locally already you can also install through there.

First clone the repository
```
git clone git@github.com:HuInstituteForICT/workplacelearning.git
```

Enter the project folder
```
cd workplacelearning
```

Now build the PHP container and install the project dependencies in it with composer

```
docker-compose build --no-cache php && docker-compose run php composer install
```

Now copy the `.env.example` to `.env`.  
You should only need to configure 2 values: `host_lan_ip` and `APP_KEY`  

* `host_lan_ip` : Change it to the LAN IP address of your host machine.  
* `APP_KEY` : run `docker-compose run php php artisan key:generate` to generate a random key 

Now start the other containers with
```
docker-compose up -d
```

Create the database schema by running the migrations with
```
docker-compose run php php artisan migrate:refresh --seed
```

Updating the database later on can be done by simply running
```
docker-compose run php php artisan migrate
```

Install should be finished  
You can now reach the following services:  

| Service       | Location      | 
| ------------- |:-------------:|
| Webserver     | localhost     |
| DB client     | localhost:3306|
| PhpMyAdmin    | localhost:8080|
| Mailcatcher   | localhost:1080|



#### Compiling assets
Javascript and CSS should be compiled before first use and after every asset update.
This can be done using Laravel Mix.

This requires that the NPM modules are installed, install them with:
```
docker exec -it wpl_php npm install
```
Or if NPM is available on host machine use `npm install`  

Now you can compile assets with
```
docker exec -it wpl_php npm run dev
```
To keep compiling on changes
``` 
docker exec -it wpl_php npm run watch
```

#### Translations
Translations can be added and modified manually by editing the right lang files in `resources/lang/*`.
Another approach is to use the Laravel Translation Manager package, available in dev environment at `localhost/translations`.
Instructions on how to use it can be found at its [github page](https://github.com/barryvdh/laravel-translation-manager).

React/JS translations are stored in `resources/lang/*/react.php`. These are compiled to the `public/messages.js` file with `php artisan lang:js`.
So don't forget to compile whenever you add new translations that are used in React/JS.

Laravel has three ways to use translations:
- Lang::get('namespace.key')
- trans('namespace.key')
- __('namespace.key') or __('string to translate') 

with the latter using .json files first. Recommended is to only use the __('') function as it will check both json and normal translations files, removing the need to decide which helper to use.

#### Xdebug
It is possible to use Xdebug with docker and PHPStorm, follow the steps below to configure it correctly.

Open the preferences (Mac: `command + ,` , Windows `ctrl + s`) and navigate to languages &amp; frameworks > php > servers  
Now create a new server with:
* Name: wpl
* Host: localhost
* Port: 80
* Use path mappings: enabled
    * Mapping: project root -> /var/www/
    
Now go to Language &amp; Frameworks > PHP > Debug, in the Xdebug settings set:
* Debug port: 9000
* Enable all 3 checkboxes

Xdebug should now be ready to use, try it by setting a breakpoint in the code and opening a page that executes that code.



## Running the tests

Tests can be ran in the IDE by configuring PHPUnit in it.  
Alternatively you can execute it in the PHP container by running:
```
docker-compose run php php ./vendor/bin/phpunit
```



