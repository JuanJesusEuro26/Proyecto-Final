#!/usr/bin/env bash

#Generar el bootstrap de Symfony
php vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php

#Limpiar cache a fondo
rm -rf app/cache/*
php app/console cache:clear --env=prod 

#Sincronizar DB 
php app/console doctrine:schema:update --force

#Assets
php app/console assets:install web

#Permisos
chmod -R 777 app/cache app/logs

#Iniciar servicios
service nginx start
php-fpm