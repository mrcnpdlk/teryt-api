# TERYT WS1 API - Polish address database

## Basic usage

### Cache
It is recommended to use [phpfastcache/phpfastcache](https://github.com/PHPSocialNetwork/phpfastcache) bundle to improve efficiency.
`phpfastcache/phpfastcache` supports a lot of endpoints, i.e. `Files`, `Sqlite`, `Redis` and many other. 
More information about using cache and configuration it you can find in this [Wiki](https://github.com/PHPSocialNetwork/phpfastcache/wiki). 

```php
use phpFastCache\CacheManager;

    /**
     * Cache in system files
     */
    $oInstanceCacheFiles = CacheManager::Files(
        [
            'defaultTtl' => 3600 * 24, // 24h
            'path'       => sys_get_temp_dir(),
        ]
    );
    /**
     * Cache Redis
     */
    $oInstanceCacheRedis = CacheManager::Redis(
        [
            "host"                => null, // default localhost
            "port"                => null, // default 6379
            'defaultTtl'          => 3600 * 24, // 24h
            'ignoreSymfonyNotice' => true,
        ]
    );

```

### Log

Library also supports logging bundle based on `Psr\Log\LoggerInterface`, i.e. very popular
[monolog/monolog](https://github.com/Seldaek/monolog).

```php

$oInstanceLogger = new \Monolog\Logger('name_of_my_logger');
$oInstanceLogger->pushHandler(new \Monolog\Handler\ErrorLogHandler());

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
|`WyszukajMiejscowoscWJPT()`|:no_entry_sign:||
|`WyszukajUlice()`|:ok_hand:||
|`WyszukajJednostkeWRejestrze()`|:ok_hand:||
|`WyszukajMiejscowoscWRejestrze()`|:ok_hand:||
|`WyszukajUliceWRejestrze()`|:ok_hand:||
|`PobierzListeRegionow()`|:no_entry_sign:||
|`PobierzListeWojewodztwWRegionie()`|:no_entry_sign:||
|`PobierzListePodregionow()`|:no_entry_sign:||
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
