<?php

/*
 * This file is part of Parasut.
 *
 * (c) Sercan Çakır <srcnckr@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acr\Shopier\Controllers\Parasut\Exception;

/**
 * UnauthorizedException
 *
 * @package   Parasut
 * @author    Sercan Çakır <srcnckr@gmail.com>
 * @license   MIT License
 * @copyright 2015
 */
class UnauthorizedException extends HttpException
{
    /**
     * Constructor.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = null)
    {
        parent::__construct('Unauthorized', 401);
    }
}
