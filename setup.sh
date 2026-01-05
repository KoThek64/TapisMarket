
cd conteneur
chmod -R 777 src

echo "----------------------------------"
echo "|     Building podman images     |"
echo "----------------------------------"
podman-compose build --no-cache 
podman-compose up > /dev/null &
PODMAN=$!

sleep 10

echo "----------------------------------"
echo "|     Installing depencencies    |"
echo "----------------------------------"
podman-compose exec web composer install

echo "----------------------------------"
echo "|     Database creation          |"
echo "----------------------------------"
podman-compose exec web php spark migrate
podman-compose exec web php spark db:seed JeuDeDonnees

kill $PODMAN


