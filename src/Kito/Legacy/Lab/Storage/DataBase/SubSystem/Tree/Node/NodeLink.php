<?php

/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 */

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class NodeLink extends Link
{
    private static $tableName = 'BLK_NODE_LINK';
    private static $tablePk = 'NODE_LINK_ID';
    private static $tableValue0 = 'NODE_LINK_NAME_ID_A';
    private static $tableValue1 = 'NODE_LINK_NAME_ID_B';

    public function __construct($cnn)
    {
        parent::__construct($cnn, self::$tableName, self::$tablePk, self::$tableValue0, self::$tableValue1);
    }
}
