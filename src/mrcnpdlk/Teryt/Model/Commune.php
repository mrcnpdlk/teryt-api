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
use mrcnpdlk\Teryt\Exception\InvalidArgument;
use mrcnpdlk\Teryt\Exception\NotFound;

class Commune
{
    /**
     * 6 (lub 7) znakowy symbol powiatu lub tercId
     * sklada sie z 2 cyfr województwa , 2 powiatu, 2 gminy i 1 rodzaj gminy (opcjonalny)
     *
     * @var string
     */
    public $id;
    /**
     * Pełen identyfikator gminy
     *
     * @var string
     */
    public $tercId;
    /**
     * Nazwa powiatu
     *
     * @var static
     */
    public $name;
    /**
     * ID typu gminy
     *
     * @var string
     */
    public $typeId;
    /**
     * Nazwa typu gminy
     *
     * @var string
     */
    public $typeName;
    /**
     * @var \mrcnpdlk\Teryt\Model\District
     */
    public $district;

    public function __construct(string $id)
    {
        switch (strlen($id)) {
            case 6;
                $provinceId = substr($id, 0, 2);
                $districtId = substr($id, 2, 2);
                $communeId  = substr($id, 2, 2);
                $tercId     = null;
                break;
            case 7:
                $provinceId = substr($id, 0, 2);
                $districtId = substr($id, 2, 2);
                $communeId  = substr($id, 2, 2);
                $tercId     = $id;
                break;
            default:
                throw new InvalidArgument(sprintf('CommuneSymbol malformed, it has %s chars', strlen($id)));
                break;
        }
        if (!$tercId) {
            foreach (Api::PobierzListeGmin($provinceId, $districtId) as $i) {
                if ($i->districtId === $districtId) {
                    $this->id       = $id;
                    $this->name     = $i->name;
                    $this->typeName = $i->typeName;
                    $this->typeId   = $i->communeTypeId;
                    $tercId         = sprintf('%s%s%s%s', $provinceId, $districtId, $communeId, $i->communeTypeId);
                }
            }

        } else {
            $res = Api::WyszukajJednostkeWRejestrze(null, Api::CATEGORY_GMI_ALL, [], [$tercId]);
            if (!empty($res) && count($res) === 1) {
                $oCommune       = $res[0];
                $this->id       = $id;
                $this->name     = $oCommune->communeName;
                $this->typeName = $oCommune->communeTypeName;
                $this->typeId   = $oCommune->communeTypeId;
            }
        }
        if (!$this->id) {
            throw new NotFound(sprintf('Commune [id:%s] not exists', $id));
        }


        $this->id       = sprintf('%s%s%s', $provinceId, $districtId, $communeId);
        $this->tercId   = $tercId;
        $this->district = District::find(sprintf('%s%s', $provinceId, $districtId));
    }

    public static function find(string $id)
    {
        return new static($id);
    }
}