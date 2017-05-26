[![BCH compliance](https://bettercodehub.com/edge/badge/HUInstituteForICT/workplacelearning?branch=master&token=83dd337c2c87d86fa2fe2cde55c50f308c1291d4)](https://bettercodehub.com/)

## Config




## Unsure value of this documentation. 
In deze webfolder (/sites/werkplekleren.hu.nl/) bevinden zich 3 mappen.

htdocs/   bevat de uitrol van 'Project Werkplekleren (25-08-2016)'
laravel/  bevat de oorspronkelijke installatie van laravel.com
tmp/      bevat copies van beide




# VirtualHost *:80

DocumentRoot      /sites/werkplekleren.hu.nl/htdocs/public
Directory         /sites/werkplekleren.hu.nl/htdocs


## Laravel errors
`Laravel default locale is not in the supportedLocales array.`
./vendor/mcamara/laravel-localization/src/config/config.php => uncomment 'nl' entry in array


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

