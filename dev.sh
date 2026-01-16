
chmod -R 777 src
cd conteneur

echo "----------------------------------"
echo "|     Building podman images     |"
echo "----------------------------------"
podman-compose -f compose.dev.yml down
podman-compose -f compose.dev.yml --env-file dev.env up -d

if [ ! -d ../src/vendor ]; then

    echo "----------------------------------"
    echo "|     Installing depencencies    |"
    echo "----------------------------------"
    podman-compose -f compose.dev.yml exec web composer install

fi

# wait for db to start
sleep 10

echo "----------------------------------"
echo "|     Database creation          |"
echo "----------------------------------"
podman-compose -f compose.dev.yml exec web php spark migrate
podman-compose -f compose.dev.yml exec web php spark db:seed DataSeeder

chmod -R 777 ../src

podman-compose -f compose.dev.yml logs -f
