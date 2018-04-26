<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace lib\Predis\Protocol\Text;

use lib\Predis\CommunicationException;
use lib\Predis\Command\CommandInterface;
use lib\Predis\Connection\CompositeConnectionInterface;
use lib\Predis\Protocol\ProtocolException;
use lib\Predis\Protocol\ProtocolProcessorInterface;
use lib\Predis\Response\Status as StatusResponse;
use lib\Predis\Response\Error as ErrorResponse;
use lib\Predis\Response\Iterator\MultiBulk as MultiBulkIterator;

/**
 * Protocol processor for the standard Redis wire protocol.
 *
 * @link http://redis.io/topics/protocol
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class ProtocolProcessor implements ProtocolProcessorInterface
{
    protected $mbiterable;
    protected $serializer;

    /**
     *
     */
    public function __construct()
    {
        $this->mbiterable = false;
        $this->serializer = new RequestSerializer();
    }

    /**
     * {@inheritdoc}
     */
    public function write(CompositeConnectionInterface $connection, CommandInterface $command)
    {
        $request = $this->serializer->serialize($command);
        $connection->writeBuffer($request);
    }

    /**
     * {@inheritdoc}
     */
    public function read(CompositeConnectionInterface $connection)
    {
        $chunk = $connection->readLine();
        $prefix = $chunk[0];
        $payload = substr($chunk, 1);

        switch ($prefix) {
            case '+':
                return new StatusResponse($payload);

            case '$':
                $size = (int) $payload;
                if ($size === -1) {
                    return null;
                }

                return substr($connection->readBuffer($size + 2), 0, -2);

            case '*':
                $count = (int) $payload;

                if ($count === -1) {
                    return null;
                }
                if ($this->mbiterable) {
                    return new MultiBulkIterator($connection, $count);
                }

                $multibulk = array();

                for ($i = 0; $i < $count; $i++) {
                    $multibulk[$i] = $this->read($connection);
                }

                return $multibulk;

            case ':':
                return (int) $payload;

            case '-':
                return new ErrorResponse($payload);

            default:
                CommunicationException::handle(new ProtocolException(
                    $connection, "Unknown response prefix: '$prefix'."
                ));

                return;
        }
    }

    /**
     * Enables or disables returning multibulk responses as specialized PHP
     * iterators used to stream bulk elements of a multibulk response instead
     * returning a plain array.
     *
     * Streamable multibulk responses are not globally supported by the
     * abstractions built-in into Predis, such as transactions or pipelines.
     * Use them with care!
     *
     * @param bool $value Enable or disable streamable multibulk responses.
     */
    public function useIterableMultibulk($value)
    {
        $this->mbiterable = (bool) $value;
    }
}
