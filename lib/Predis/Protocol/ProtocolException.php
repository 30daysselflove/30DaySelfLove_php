<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace lib\Predis\Protocol;

use lib\Predis\CommunicationException;

/**
 * Exception used to indentify errors encountered while parsing the Redis wire
 * protocol.
 *
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class ProtocolException extends CommunicationException
{
}