[![BCH compliance](https://bettercodehub.com/edge/badge/HUInstituteForICT/workplacelearning?branch=master&token=83dd337c2c87d86fa2fe2cde55c50f308c1291d4)](https://bettercodehub.com/)

# WorkplaceLearning

## Getting Started

These instructions will help setup the development environment using docker

### Prerequisites

- Docker
- PHPStorm to make use of pre-configured Xdebug

### Installing

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







## JS / ReactJS compiling
1. use NPM or yarn to install dependencies.
2. use `npm run dev` to compile once or `npm run watch`, on production use `npm run production`

### Docker
Edit the `host_lan_ip` in `docker-compose.yml` to reflect your local IP address to use xdebug

Webserver is available at `localhost:80`  
Database clients like mysql workbench can access the DB at `localhost:3306`  
PHPMyAdmin is available at `localhost:8080`  
Mailcatcher is available at `localhost:1080`  
  
Run with `docker-compose up -d`  
Access a container with `docker exec -it CONTAINER_NAME bash`  
  
When using PHPStorm don't forget to add a server with the name `wpl` and set correct path mappings

#### Database migrations
**note: running this on a database already in use will reset it to this default state**
##### First time  
Using the migrations you will receive a complete up-to-date database with a default state. 
This default state includes things as categories, resource material etc.  

To create the default state run `php artisan migrate:refresh --seed`  
**if using docker run**`docker exec wpl_php php artisan migrate:refresh --seed`

##### Other times
To update your database run `php artisan migrate`  
**if using docker run**`docker exec wpl_php php artisan migrate`



