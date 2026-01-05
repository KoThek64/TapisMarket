
chmod -R 777 src
cd conteneur

echo "----------------------------------"
echo "|     Building podman images     |"
echo "----------------------------------"
podman-compose up -d

if [ ! -f ../src/vendor ]; then

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
podman-compose exec web php spark db:seed JeuDeDonnees

chmod -R 777 ../src

podman-compose logs -f
