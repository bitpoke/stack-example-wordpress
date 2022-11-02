FROM docker.io/bitpoke/wordpress-runtime:6.1
# to add files to the webroot, place them in the `webroot` folder and uncomment
# the following line
# COPY --chown=www-data:www-data webroot/ /app/web/
EXPOSE 8080
