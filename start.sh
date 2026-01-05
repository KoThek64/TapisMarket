
chmod -R 777 src
cd conteneur

podman-compose down

echo "----------------------------------"
echo "|     Building podman images     |"
echo "----------------------------------"
podman-compose down
podman-compose up -d

if [ ! -d ../src/vendor ]; then

    echo "----------------------------------"
    echo "|     Installing depencencies    |"
    echo "----------------------------------"
    podman-compose exec web composer install

fi

# wait for db to start
sleep 10

echo "----------------------------------"
echo "|     Database creation          |"
echo "----------------------------------"
podman-compose exec web php spark migrate
podman-compose exec web php spark db:seed DataSeeder

chmod -R 777 ../src

podman-compose logs -f
