<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2017 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 07.09.2017
 */

namespace mrcnpdlk\Teryt\Model;

use mrcnpdlk\Teryt\Exception\NotFound;
use mrcnpdlk\Teryt\NativeApi;

/**
 * Class District
 */
class District extends EntityAbstract
{
    /**
     * 4 znakowy symbol powiatu
     * sklada sie z 2 cyfr województwa i 2 powiatu
     *
     * @var string
     */
    public $id;
    /**
     * Nazwa powiatu
     *
     * @var string
     */
    public $name;
    /**
     * Nazwa typu powiatu
     *
     * @var string
     */
    public $typeName;
    /**
     * Obiekt z informacjami o wojewodztwie
     *
     * @var \mrcnpdlk\Teryt\Model\Province
     */
    public $province;

    /**
     * District constructor.
     *
     * @param string $id 4-znakowy symbol powiatu
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws NotFound
     *
     * @return $this
     */
    public function find(string $id)
    {
        $provinceId = substr($id, 0, 2);
        $districtId = substr($id, 2, 2);
        foreach (NativeApi::getInstance()->PobierzListePowiatow($provinceId) as $i) {
            if ($i->districtId === $districtId) {
                $this->id       = $id;
                $this->name     = $i->name;
                $this->typeName = $i->typeName;
            }
        }
        if (!$this->id) {
            throw new NotFound(sprintf('District [id:%s] not exists', $id));
        }
        $this->province = (new Province())->find($provinceId);

        return $this;
    }
}
