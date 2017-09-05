<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 05.09.2017
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Exception;
use mrcnpdlk\Teryt\Exception\NotFound;

class Province
{
    /**
     * Province IP - two chars
     *
     * @var string
     */
    protected $id;
    /**
     * @var \mrcnpdlk\Teryt\Model\ProvinceData
     */
    protected $oData;

    /**
     * @var \mrcnpdlk\Teryt\Client
     */
    private $oClient;

    public static function create(string $id)
    {
        return new static($id);
    }

    public function __construct(string $id)
    {
        $this->oClient = Client::getInstance();
        $self          = $this;

        /**
         * @var \mrcnpdlk\Teryt\Model\ProvinceData $res
         */
        $res = $this->oClient->useCache(
            function () use ($self, $id) {
                foreach (Province::getAll() as $p) {
                    if ($p->id === $id) {
                        return $p;
                    }
                }
                throw new NotFound(sprintf('Province [id:%s] not found', $id));
            },
            md5(json_encode([__METHOD__, $id]))
        );

        $this->oData = $res;
        $this->id    = $res->id;
    }

    public function getData()
    {
        return $this->oData;
    }

    public static function getAll()
    {
        $answer = [];
        $res    = Client::getInstance()->getResponse('PobierzListeWojewodztw');
        if (isset($res->JednostkaTerytorialna)) {
            foreach ($res->JednostkaTerytorialna as $p) {
                $answer[] = ProvinceData::create($p);
            };

            return $answer;
        } else {
            throw new Exception(sprintf('%s Empty response', __METHOD__));
        }
    }

}