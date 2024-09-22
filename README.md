# Naruto RPG

## Overview

### Server Requirements

To run Naruto RPG we recommend your host supports:

* PHP version 8.1 or greater.
* MariaDB version 10.6 or greater.
* HTTPS support

#### Required PHP extensions

* PDO/MySQL

## Development

### Requirements

- Docker - [Install](https://docs.docker.com/get-docker/)
- DDEV - [Install](https://ddev.readthedocs.io/en/stable/)

### Installation

1. Run `ddev start` to start the docker containers.
2. Run `ddev composer install` to install all dependencies.

### Running xdebug inside DDEV container

DDEV provides xdebug directly. It can be activated and deactivated at runtime.

Enable it by executing:

```sh
$ ddev xdebug on
```

Disable it by executing:

```sh
$ ddev xdebug off
```
