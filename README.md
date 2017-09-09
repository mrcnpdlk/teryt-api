
[![Total Downloads](http://img.shields.io/packagist/dt/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api) 
[![Latest Stable Version](http://img.shields.io/packagist/v/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api) 
[![License](https://img.shields.io/packagist/l/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api)    
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/?branch=master) 
[![Build Status](https://travis-ci.org/mrcnpdlk/teryt-api.svg?branch=master)](https://travis-ci.org/mrcnpdlk/teryt-api)

[![Dependency Status](https://www.versioneye.com/user/projects/59b2679a368b08003d0e8455/badge.svg)](https://www.versioneye.com/user/projects/59b2679a368b08003d0e8455?child=summary) 

[![Latest Stable Version](https://poser.pugx.org/mrcnpdlk/teryt-api/v/stable)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Latest Unstable Version](https://poser.pugx.org/mrcnpdlk/teryt-api/v/unstable.png)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Monthly Downloads](https://poser.pugx.org/mrcnpdlk/teryt-api/d/monthly)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Daily Downloads](https://poser.pugx.org/mrcnpdlk/teryt-api/d/daily)](https://packagist.org/packages/mrcnpdlk/teryt-api)

[![Code Climate](https://codeclimate.com/github/mrcnpdlk/teryt-api/badges/gpa.svg)](https://codeclimate.com/github/mrcnpdlk/teryt-api) 
[![Issue Count](https://codeclimate.com/github/mrcnpdlk/teryt-api/badges/issue_count.svg)](https://codeclimate.com/github/mrcnpdlk/teryt-api)


# TERYT WS1 API - Polish address database

## Instalation

Install the latest version with
```bash
composer require mrcnpdlk/teryt-api
```

## Basic usage

### Cache
Library suport Cache bundles based on [PSR-16](http://www.php-fig.org/psr/psr-16/) standard.

For below example was used [phpfastcache/phpfastcache](https://github.com/PHPSocialNetwork/phpfastcache).
`phpfastcache/phpfastcache` supports a lot of endpoints, i.e. `Files`, `Sqlite`, `Redis` and many other. 
More information about using cache and configuration it you can find in this [Wiki](https://github.com/PHPSocialNetwork/phpfastcache/wiki). 

```php

    /**
     * Cache in system files
     */
    $oInstanceCacheFiles = new \phpFastCache\Helper\Psr16Adapter(
        'files',
        [
            'defaultTtl' => 3600 * 24, // 24h
            'path'       => sys_get_temp_dir(),
        ]);
    /**
     * Cache in Redis
     */
    $oInstanceCacheRedis = new \phpFastCache\Helper\Psr16Adapter(
        'redis',
        [
            "host"                => null, // default localhost
            "port"                => null, // default 6379
            'defaultTtl'          => 3600 * 24, // 24h
            'ignoreSymfonyNotice' => true,
        ]);

```

### Log

Library also supports logging packages based on [PSR-3](http://www.php-fig.org/psr/psr-3/) standard, i.e. very popular
[monolog/monolog](https://github.com/Seldaek/monolog).

```php

$oInstanceLogger = new \Monolog\Logger('name_of_my_logger');
$oInstanceLogger->pushHandler(new \Monolog\Handler\ErrorLogHandler(
        \Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM,
        \Psr\Log\LogLevel::DEBUG
    )
);

```

### Teryt Authentication
Service TERYT WS1 shares API based on `SOAP Protocol`. More information about service you can find on 
this [site](http://eteryt.stat.gov.pl/eTeryt/rejestr_teryt/udostepnianie_danych/baza_teryt/usluga_sieciowa_interfejsy_api/jakkorzystac.aspx)
There are two ways to connect to the server:
 - `production database` - you need login/passwor
 - `testing database` - default authentication with default login/password pair

First of all we need configure connection calling `setTerytConfig()` method and 
optionaly set cache and log instrances

```php
use mrcnpdlk\Teryt\Client;

Client::create()
      ->setTerytConfig([
          'username' => 'secret_login',
          'password' => 'secret_password',
      ])
      ->setCacheInstance($oInstanceCacheRedis)
      ->setLogger($oInstanceLogger)
;
```

After that we able to call auxiliary static methods defined in Api class, i.e:
```php
var_dump(\mrcnpdlk\Teryt\Api::PobierzSlownikCechULIC());
var_dump(\mrcnpdlk\Teryt\Api::WyszukajMiejscowosc('skiernie',null));
```

## Defined methods
Almost all methods from official documentation have been mapped and defined.

Full list below:

| Method | Status | Description|
| ------ | ------ |------ |
|`CzyZalogowany()`|:ok_hand:||
|`PobierzListeWojewodztw()`|:ok_hand:||
|`PobierzListePowiatow()`|:ok_hand:||
|`PobierzListeGmin()`|:ok_hand:||
|`PobierzGminyiPowDlaWoj()`|:ok_hand:||
|`PobierzListeUlicDlaMiejscowosci()`|:warning:||
|`PobierzListeMiejscowosciWGminie()`|:warning:||
|`PobierzListeMiejscowosciWRodzajuGminy()`|:ok_hand:||
|`PobierzSlownikRodzajowJednostek()`|:ok_hand:||
|`PobierzSlownikRodzajowSIMC()`|:ok_hand:||
|`PobierzSlownikCechULIC()`|:ok_hand:||
|`WeryfikujAdresDlaMiejscowosci()`|:ok_hand:||
|`WeryfikujAdresDlaMiejscowosciAdresowy()`|:ok_hand:||
|`WeryfikujAdresWmiejscowosci()`|:ok_hand:||
|`WeryfikujAdresWmiejscowosciAdresowy()`|:ok_hand:||
|`WeryfikujAdresDlaUlic()`|:ok_hand:||
|`WeryfikujAdresDlaUlicAdresowy()`|:ok_hand:||
|`WyszukajJPT()`|:warning:|empty response|
|`WyszukajMiejscowosc()`|:ok_hand:||
|`WyszukajMiejscowoscWJPT()`|:ok_hand:||
|`WyszukajUlice()`|:ok_hand:||
|`WyszukajJednostkeWRejestrze()`|:ok_hand:||
|`WyszukajMiejscowoscWRejestrze()`|:ok_hand:||
|`WyszukajUliceWRejestrze()`|:ok_hand:||
|`PobierzListeRegionow()`|:ok_hand:||
|`PobierzListeWojewodztwWRegionie()`|:ok_hand:||
|`PobierzListePodregionow()`|:ok_hand:||
|`PobierzListePowiatowWPodregionie()`|:no_entry_sign:||
|`PobierzListeGminPowiecie()`|:no_entry_sign:||
|`PobierzKatalogTERCAdr()`|:no_entry_sign:||
|`PobierzKatalogTERC()`|:no_entry_sign:||
|`PobierzKatalogNTS()`|:no_entry_sign:||
|`PobierzKatalogSIMCAdr()`|:no_entry_sign:||
|`PobierzKatalogSIMC()`|:no_entry_sign:||
|`PobierzKatalogSIMCStat()`|:no_entry_sign:||
|`PobierzKatalogULIC()`|:no_entry_sign:||
|`PobierzKatalogULICAdr()`|:no_entry_sign:||
|`PobierzKatalogULICBezDzielnic()`|:no_entry_sign:||
|`PobierzKatalogWMRODZ()`|:no_entry_sign:||
|`PobierzZmianyTercUrzedowy()`|:no_entry_sign:||
|`PobierzZmianyTercAdresowy()`|:no_entry_sign:||
|`PobierzZmianyNTS()`|:no_entry_sign:||
|`PobierzZmianySimcUrzedowy()`|:no_entry_sign:||
|`PobierzZmianySimcAdresowy()`|:no_entry_sign:||
|`PobierzZmianySimcStatystyczny()`|:no_entry_sign:||
|`PobierzZmianyUlicUrzedowy()`|:no_entry_sign:||
|`PobierzZmianyUlicAdresowy()`|:no_entry_sign:||
|`WeryfikujNazwaAdresUlic()`|:no_entry_sign:||
|`WeryfikujNazwaAdresUlicAdresowy()`|:no_entry_sign:||
|`PobierzDateAktualnegoKatTerc()`|:no_entry_sign:||
|`PobierzDateAktualnegoKatNTS()`|:no_entry_sign:||
|`PobierzDateAktualnegoKatSimc()`|:no_entry_sign:||
|`PobierzDateAktualnegoKatUlic()`|:no_entry_sign:||
