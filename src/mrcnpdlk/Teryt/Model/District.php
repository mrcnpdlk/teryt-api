<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2017 pudelek.org.pl
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * Author Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 07.09.2017
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Api;
use mrcnpdlk\Teryt\Exception\NotFound;

class District
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
     * @var static
     */
    public $name;
    /**
     * Nazwa typu powiatu
     *
     * @var string
     */
    public $typeName;
    /**
     * @var \mrcnpdlk\Teryt\Model\Province
     */
    public $province;

    public function __construct(string $id)
    {
        $provinceId = substr($id, 0, 2);
        $districtId = substr($id, 2, 2);
        foreach (Api::PobierzListePowiatow($provinceId) as $i) {
            if ($i->districtId === $districtId) {
                $this->id       = $id;
                $this->name     = $i->name;
                $this->typeName = $i->typeName;
            }
        }
        if (!$this->id) {
            throw new NotFound(sprintf('District [id:%s] not exists', $id));
        }
        $this->province = Province::find($provinceId);
    }

    public static function find(string $id)
    {
        return new static($id);
    }
}