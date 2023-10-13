#!/bin/bash

rsync -avzq --ignore-existing conf/env/init.docker-compose.yml docker-compose.yml
rsync -avzq --ignore-existing conf/env/init.env .env

sed -i "s/UID=1000/UID=$(id -u)/g" .env
sed -i "s/GID=1000/GID=$(id -g)/g" .env
