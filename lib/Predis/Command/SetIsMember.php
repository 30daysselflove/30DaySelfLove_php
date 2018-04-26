<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace lib\Predis\Command;

/**
 * @link http://redis.io/commands/sismember
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class SetIsMember extends Command
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'SISMEMBER';
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($data)
    {
        return (bool) $data;
    }
}
