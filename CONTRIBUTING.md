Contributing
-------------

Before you contribute code to php-telegram-bot, please make sure it conforms to the PSR-2 coding standard and that the php-telegram-bot unit tests still pass. The easiest way to contribute is to work on a checkout of the repository, or your own fork. If you do this, you can run the following commands to check if everything is ready to submit:

    cd php-telegram-bot
    composer update
    ./vendor/bin/phpcs --standard=phpcs.xml -sp --encoding=utf-8 src/ --report-width=150

Which should give you no output, indicating that there are no coding standard errors. And then:

    ./vendor/bin/phpunit

Which should give you no failures or errors. You can ignore any skipped tests as these are for external tools.

Pushing
-------

Development is based on the git flow branching model (see http://nvie.com/posts/a-successful-git-branching-model/ )
If you fix a bug please push in hotfix branch.
If you develop a new feature please create a new branch.

Version
-------

Version number: 0.#version.#hotfix

Further code convention adopted
-------------------------------

- Each method and class is documented with a docblock

Example for a function or methods:
```
/**
 * Get formatted date
 *
 * @param string $location
 *
 * @return string
 */
```

- Each file is provided with the following header:
```
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
```
