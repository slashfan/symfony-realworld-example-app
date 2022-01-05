# ![RealWorld Example App](logo.png)

> ### Symfony codebase containing real world examples (CRUD, auth, advanced patterns, etc) that adheres to the [RealWorld](https://github.com/gothinkster/realworld) spec and API.

[![Code Coverage](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/badges/build.png?b=master)](https://scrutinizer-ci.com/g/slashfan/symfony-realworld-example-app/build-status/master)

This codebase was created to demonstrate a fully fledged fullstack application built with **Symfony** including CRUD operations, authentication, routing, pagination, and more.

We've gone to great lengths to adhere to the **Symfony** community styleguides & best practices.

For more information on how to this works with other frontends/backends, head over to the [RealWorld](https://github.com/gothinkster/realworld) repo.

# Getting started

```bash
$ git clone https://github.com/slashfan/symfony-realworld-example-app
$ cd symfony-realworld-example-app
```

# Run project (with docker)

On first run :

```bash
$ make install
```

On next runs :

```bash
$ make start
```

# Run phpunit tests + api spec compliance tests + qa tools (with docker)

```bash
$ make ci
$ make specs
```
