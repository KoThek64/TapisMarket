cp -R src conteneur/app_php/src
podman-compose -f conteneur/compose.prod.yml up -d
rm -drf conteneur/app_php/src
