<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 */

namespace Kito\String;

use Kito\Math\Integer;
use Kito\Storage\DataBase\SQL\MySQL;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Storage
{
    private $driver;
    private $prefix;

    public function __construct(MySQL $driver, string $prefix = 'string')
    {
        $this->driver = $driver;
        $this->prefix = $prefix;
    }

    private function getBaseId(string $string): int
    {
        return mb_strlen($string);
    }

    private function getTableName(int $baseId): string
    {
        $tableName = $this->prefix.'__'.dechex($baseId);

        static $_ = null;

        if ($_ === null) {
            $_ = [];
        }

        $type = $baseId < 256 ? 'char' : 'varchar';
        if (!isset($_[$tableName])) {
            $_[$tableName] = $this->driver->command('CREATE TABLE IF NOT EXISTS `'.$tableName."` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `value` $type(".$baseId.') NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;');
        }

        return $tableName;
    }

    public function getId(string $string): int
    {
        $baseId = $this->getBaseId($string);
        $tableName = $this->getTableName($baseId);
        $subId = $this->driver->autoTable($tableName, ['value' => $string], ['id'])['id'];

        return Integer::mergeInteger($baseId, $subId);
    }

    public function getString(int $id): string
    {
        $_ = Integer::splitInteger($id);
        $baseId = $_[0];
        $subId = $_[1];
        $tableName = $this->getTableName($baseId);

        return $this->driver->getText($tableName, 'value', ['id' => $subId]);
    }
}
