<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 */

/**
 * @author TheKito <blankitoracing@gmail.com>
 */
interface IResultSet
{
    //NAV

    /**
     * next element in resultset.
     *
     * @return bool
     */
    public function next();

    /**
     * last element in resultset.
     *
     * @return bool
     */
    public function last();

    /**
     * previous element in resultset.
     *
     * @return bool
     */
    public function prev();

    /**
     * first element in resultset.
     *
     * @return bool
     */
    public function first();

    /**
     * move to $pos eleent in resultset.
     *
     * @return bool
     */
    public function move($pos);

    //Read

    /**
     * get row in resultset as array with index colname.
     *
     * @return Array<String,mixed>
     */
    public function get();

    /**
     * get total elements in resultset.
     *
     * @return int
     */
    public function count();

    //Other

    /**
     * freememory in resultset.
     *
     * @return bool
     */
    public function flush();
}
