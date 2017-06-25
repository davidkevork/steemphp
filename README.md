# steemphp


[![Build Status](https://api.travis-ci.org/davidkevork/steemphp.svg?branch=master)](https://travis-ci.org/davidkevork/steemphp) [![Coverage Status](https://coveralls.io/repos/github/davidkevork/steemphp/badge.svg?branch=master)](https://coveralls.io/github/davidkevork/steemphp?branch=master)

## Introduction

> This Project is Steem Client Api in PHP based on the official steemit steem.js https://github.com/steemit/steem-js/

## Install in your project

Run the command in your project folder:

```
composer require davidkevork/steemphp:dev-master
```

Or modify your 'composer.json' to include:

```
{
  "name": "yourname/projectname",
  "require": {
    "davidkevork/steemphp": "master"
  }
}
```

## Development

```
git clone https://github.com/davidkevork/steemphp.git
cd steemphp
composer install
```

`phpunit` within the folder should execute all unit tests for this project. If you're on OSX using entr (`brew install entr`), you can run the following command for live testing as you develop:

```
find src/ tests/ | entr -c phpunit
```

## License

This project is licensed under the [MIT license](LICENSE).