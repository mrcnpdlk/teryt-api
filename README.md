
[![Latest Stable Version](https://img.shields.io/github/release/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Latest Unstable Version](https://poser.pugx.org/mrcnpdlk/teryt-api/v/unstable.png)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Total Downloads](https://img.shields.io/packagist/dt/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![Monthly Downloads](https://img.shields.io/packagist/dm/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api)
[![License](https://img.shields.io/packagist/l/mrcnpdlk/teryt-api.svg)](https://packagist.org/packages/mrcnpdlk/teryt-api)    

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/?branch=master) 
[![Build Status](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/teryt-api/?branch=master)

[![Code Climate](https://codeclimate.com/github/mrcnpdlk/teryt-api/badges/gpa.svg)](https://codeclimate.com/github/mrcnpdlk/teryt-api) 
[![Issue Count](https://codeclimate.com/github/mrcnpdlk/teryt-api/badges/issue_count.svg)](https://codeclimate.com/github/mrcnpdlk/teryt-api)


[![Dependency Status](https://www.versioneye.com/user/projects/59b2679a368b08003d0e8455/badge.svg)](https://www.versioneye.com/user/projects/59b2679a368b08003d0e8455?child=summary) 


# TERYT WS1 API - Polish address database

## Installation

Install the latest version with [composer](https://packagist.org/packages/mrcnpdlk/teryt-api)
```bash
composer require mrcnpdlk/teryt-api
```

## Basic usage

### Cache
Library supports Cache bundles based on [PSR-16](http://www.php-fig.org/psr/psr-16/) standard.

For below example was used [phpfastcache/phpfastcache](https://github.com/PHPSocialNetwork/phpfastcache).
`phpfastcache/phpfastcache` supports a lot of endpoints, i.e. `Files`, `Sqlite`, `Redis` and many other. 
More information about using cache and configuration it you can find in this [Wiki](https://github.com/PHPSocialNetwork/phpfastcache/wiki). 

```php

    /**
     * Cache in system files
     */
    $oInstanceCacheFiles = new \phpFastCache\Helper\Psr16Adapter('files');

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
 - `production database` - you need login/password
 - `testing database` - default authentication with default login/password pair

First of all we need configure connection calling `setTerytConfig()` method and 
optionally set cache and log instances

```php
use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\NativeApi

$oConfig = new Config([
    'logger'       => $oInstanceLogger,
    'cache'        => $oInstanceCacheFiles,
    'username'     => $login,
    'password'     => $pass,
    'isProduction' => true,
]);
$oNativeApi = NativeApi::create($oConfig);
```

After that we able to call auxiliary static methods defined in NativeApi class, i.e:
```php
var_dump($NativeApi->CzyZalogowany());
var_dump($NativeApi->PobierzSlownikCechULIC());
var_dump($NativeApi->WyszukajMiejscowosc('skiernie',null));
```

## Defined methods (`\mrcnpdlk\Teryt\NativeApi`)
Almost all methods from official documentation have been mapped and defined.

Full list below `\mrcnpdlk\Teryt\NativeApi`:

### General


| Method | Status | Description|
| ------ | ------ |------ |
|`CzyZalogowany()`|:ok_hand:||
|`PobierzDateAktualnegoKatTerc()`|:ok_hand:||
|`PobierzDateAktualnegoKatNTS()`|:ok_hand:||
|`PobierzDateAktualnegoKatSimc()`|:ok_hand:||
|`PobierzDateAktualnegoKatUlic()`|:ok_hand:||

### Catalog TERC

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzListeWojewodztw()`|:ok_hand:||
|`PobierzListePowiatow()`|:ok_hand:||
|`PobierzListeGmin()`|:ok_hand:||
|`PobierzGminyiPowDlaWoj()`|:ok_hand:||

### Catalog NTS

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzListeRegionow()`|:ok_hand:||
|`PobierzListeWojewodztwWRegionie()`|:ok_hand:||
|`PobierzListePodregionow()`|:ok_hand:||
|`PobierzListePowiatowWPodregionie()`|:ok_hand:||
|`PobierzListeGminPowiecie()`|:ok_hand:||

### Catalog ULIC

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzListeUlicDlaMiejscowosci()`|:ok_hand:||

### Catalog SIMC

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzListeMiejscowosciWGminie()`|:ok_hand:||
|`PobierzListeMiejscowosciWRodzajuGminy()`|:ok_hand:||

### Dictionary

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzSlownikRodzajowJednostek()`|:ok_hand:||
|`PobierzSlownikRodzajowSIMC()`|:ok_hand:||
|`PobierzSlownikCechULIC()`|:ok_hand:||

### Catalog

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzKatalogTERCAdr()`|:ok_hand:||
|`PobierzKatalogTERC()`|:ok_hand:||
|`PobierzKatalogNTS()`|:ok_hand:||
|`PobierzKatalogSIMCAdr()`|:ok_hand:||
|`PobierzKatalogSIMC()`|:ok_hand:||
|`PobierzKatalogSIMCStat()`|:ok_hand:||
|`PobierzKatalogULIC()`|:ok_hand:||
|`PobierzKatalogULICAdr()`|:ok_hand:||
|`PobierzKatalogULICBezDzielnic()`|:ok_hand:||
|`PobierzKatalogWMRODZ()`|:ok_hand:||

### Changes

| Method | Status | Description|
| ------ | ------ |------ |
|`PobierzZmianyTercUrzedowy()`|:ok_hand:||
|`PobierzZmianyTercAdresowy()`|:ok_hand:||
|`PobierzZmianyNTS()`|:ok_hand:||
|`PobierzZmianySimcUrzedowy()`|:ok_hand:||
|`PobierzZmianySimcAdresowy()`|:ok_hand:||
|`PobierzZmianySimcStatystyczny()`|:ok_hand:||
|`PobierzZmianyUlicUrzedowy()`|:ok_hand:||
|`PobierzZmianyUlicAdresowy()`|:ok_hand:||

### Verification

| Method | Status | Description|
| ------ | ------ |------ |
|`WeryfikujAdresDlaMiejscowosci()`|:ok_hand:||
|`WeryfikujAdresDlaMiejscowosciAdresowy()`|:ok_hand:||
|`WeryfikujAdresWmiejscowosci()`|:ok_hand:||
|`WeryfikujAdresWmiejscowosciAdresowy()`|:ok_hand:||
|`WeryfikujAdresDlaUlic()`|:ok_hand:||
|`WeryfikujAdresDlaUlicAdresowy()`|:ok_hand:||
|`WeryfikujNazwaAdresUlic()`|:warning:|empty response|
|`WeryfikujNazwaAdresUlicAdresowy()`|:ok_hand:||

### Search

| Method | Status | Description|
| ------ | ------ |------ |
|`WyszukajJPT()`|:warning:|empty response|
|`WyszukajMiejscowosc()`|:ok_hand:||
|`WyszukajMiejscowoscWJPT()`|:ok_hand:||
|`WyszukajUlice()`|:ok_hand:||
|`WyszukajJednostkeWRejestrze()`|:ok_hand:||
|`WyszukajMiejscowoscWRejestrze()`|:ok_hand:||
|`WyszukajUliceWRejestrze()`|:ok_hand:||

## Defined methods (`\mrcnpdlk\Teryt\Api`)

| Method | Status | Description|
| ------ | ------ |------ |
|`getCity()`|:ok_hand:||

```php
$oApi = new \mrcnpdlk\Teryt\Api($oClient);
print_r($oApi->getCity('0700884'));
```

```text
mrcnpdlk\Teryt\Model\City Object
(
    [id] => 0700884
    [parentId] => 0700884
    [rmId] => 01
    [rmName] => wieś
    [name] => Burzenin
    [commune] => mrcnpdlk\Teryt\Model\Commune Object
        (
            [id] => 101414
            [tercId] => 1014052
            [name] => Burzenin
            [typeId] => 2
            [typeName] => gmina wiejska
            [district] => mrcnpdlk\Teryt\Model\District Object
                (
                    [id] => 1014
                    [name] => sieradzki
                    [typeName] => powiat
                    [province] => mrcnpdlk\Teryt\Model\Province Object
                        (
                            [id] => 10
                            [name] => ŁÓDZKIE
                        )
                )
        )
)
```
