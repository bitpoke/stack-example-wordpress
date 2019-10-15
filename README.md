stack-example-wordpress
===

Example project for running a classic WordPress setup on
[Presslabs Stack](https://www.presslabs.com/stack).

## Quickstart

Just fork this repo and you are good to go. After cloning your repo you need to
install WordPress into wp folder. To do so, you can run:

```console
$ wp core download --path=wp
```

## Building docker images

Presslabs Stack provides a base image for building and developing classic
WordPress sites. The `Dockerfile` is as simple as:

```Dockerfile
FROM quay.io/presslabs/wordpress-runtime:5.2.4
```

## Development

Local development can be done as you normally do, either running php locally,
or using docker or vagrant. The recommended way is to use docker-compose.

### wp-cli local server

This repo comes with wp-cli configured. To start the server you just need to
run:

```console
$ wp server
```

### docker and docker-compose

The [docker-compose.yaml](docker-compose.yaml) in this repo provides a good
starting point for doing local development with docker. To boot up WordPress and
MySQL server just run:

```console
$ docker-compose up -d
```

_NOTE_: If you are using docker compose, remember that the image built from the
above `Dockerfile` already includes nginx and it's accessible on port 8080. For
customizing the environment also check
[https://github.com/presslabs/stack-runtimes/blob/master/php/README.md](https://github.com/presslabs/stack-runtimes/blob/master/php/README.md).
