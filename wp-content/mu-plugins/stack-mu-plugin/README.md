stack-mu-plugin
[![Build Status](https://stack-ci.presslabs.net/api/badges/presslabs/stack-mu-plugin/status.svg)](https://stack-ci.presslabs.net/presslabs/stack-mu-plugin)
===
Presslabs Stack must use plugin for WordPress.

It provides integration for [Presslabs Stack](https://www.presslabs.com/stack)
functionalities with WordPress, such as:
* uploading and serving media files from object storage systems such as
  Google Cloud Storage or AWS S3
* object-cache implementation on top of memcached

## Install

### Bedrock

When using bedrock, just run:

```console
$ composer require presslabs/stack-mu-plugin
```

### WordPress plugin

To run as WordPress classic mu-plugin, download the plugin archive from
[https://github.com/presslabs/stack-mu-plugin/releases](https://github.com/presslabs/stack-mu-plugin/releases)
and extract it into your `wp-content/mu-plugins` folder.

Then you need to activate the mu-plugin, by copying `stack-mu-plugin.php` from
`wp-content/mu-plugins/stakc-mu-plugin` into your `wp-content/mu-plugins`
folder.

```console
$ cp wp-content/mu-plugins/stack-mu-plugin/stack-mu-plugin.php wp-content/mu-plugins/
```

### WordPress Object Cache

In order to use the custom object cache, you'll need to copy it into the root of
`WP_CONTENT_DIR` (usually `wp-content`).

```console
$ cp wp-content/mu-plugins/stack-mu-plugin/src/object-cache.php wp-content/
```

## Development

Clone this repository, copy `.env.example` to `.env` and edit it accordingly.

To install dependencies just run
```console
$ make dependencies
```

### Development server

To start a local development server you need wp-cli installed. To start the
development server, just run

```console
$ wp server
```

### Testing

Running plugin tests:

```console
$ make test-runtime
```

Running integration tests:

```console
$ make test-runtime
```
