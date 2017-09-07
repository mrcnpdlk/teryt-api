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
use mrcnpdlk\Teryt\Exception;

class Province
{
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $id;
    /**
     * Nazwa województwa
     *
     * @var static
     */
    public $name;

    public function __construct(string $id)
    {
        foreach (Api::PobierzListeWojewodztw() as $w) {
            if ($w->provinceId === $id) {
                $this->id   = $w->provinceId;
                $this->name = $w->name;
            }
        }
        if (!$this->id) {
            throw new Exception\NotFound(sprintf('Province [id:%s] not exists', $id));
        }
    }

    public static function find(string $id)
    {
        return new static($id);
    }
}