# Kautilities

[![Join the chat at https://gitter.im/akalongman/kautilities](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/akalongman/kautilities?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/akalongman/kautilities.svg?branch=master)](https://travis-ci.org/akalongman/kautilities)
[![Latest Stable Version](https://img.shields.io/packagist/v/Longman/Kautilities.svg)](https://packagist.org/packages/longman/kautilities)
[![Total Downloads](https://img.shields.io/packagist/dt/Longman/Kautilities.svg)](https://packagist.org/packages/longman/kautilities)
[![Downloads Month](https://img.shields.io/packagist/dm/Longman/Kautilities.svg)](https://packagist.org/packages/longman/kautilities)
[![License](https://img.shields.io/packagist/l/Longman/Kautilities.svg)](https://packagist.org/packages/stichoza/longman/kautilities)


PHP class for convert KA letters to LAT and back


## Installation

Install this package through [Composer](https://getcomposer.org/). Edit your project's `composer.json` file to require `longman/kautilities`.

```json
"require": {
    "longman/kautilities": "~1.0"
}
```

**Or** run a command in your command line:

```
composer require longman/kautilities
```

## Usage

```php
use Longman\Kautilities\Converter;

echo Converter::kaToLat('ა ბ გ დ');    // output: a b g d

echo Converter::latToKa('a b g d');    // output: ა ბ გ დ


```
