ARG TAG=6.5.3
FROM docker.io/bitpoke/wordpress-runtime:${TAG}
# to add files to the webroot, place them in the `webroot` folder and uncomment
# the following line
# COPY --chown=www-data:www-data webroot/ /app/web/
EXPOSE 8080
