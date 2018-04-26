<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace lib\Predis\Profile;

/**
 * Server profile for Redis 2.6.
 *
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class RedisVersion260 extends RedisProfile
{
    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return '2.6';
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedCommands()
    {
        return array(
            /* ---------------- Redis 1.2 ---------------- */

            /* commands operating on the key space */
            'EXISTS'                    => 'lib\Predis\Command\KeyExists',
            'DEL'                       => 'lib\Predis\Command\KeyDelete',
            'TYPE'                      => 'lib\Predis\Command\KeyType',
            'KEYS'                      => 'lib\Predis\Command\KeyKeys',
            'RANDOMKEY'                 => 'lib\Predis\Command\KeyRandom',
            'RENAME'                    => 'lib\Predis\Command\KeyRename',
            'RENAMENX'                  => 'lib\Predis\Command\KeyRenamePreserve',
            'EXPIRE'                    => 'lib\Predis\Command\KeyExpire',
            'EXPIREAT'                  => 'lib\Predis\Command\KeyExpireAt',
            'TTL'                       => 'lib\Predis\Command\KeyTimeToLive',
            'MOVE'                      => 'lib\Predis\Command\KeyMove',
            'SORT'                      => 'lib\Predis\Command\KeySort',
            'DUMP'                      => 'lib\Predis\Command\KeyDump',
            'RESTORE'                   => 'lib\Predis\Command\KeyRestore',

            /* commands operating on string values */
            'SET'                       => 'lib\Predis\Command\StringSet',
            'SETNX'                     => 'lib\Predis\Command\StringSetPreserve',
            'MSET'                      => 'lib\Predis\Command\StringSetMultiple',
            'MSETNX'                    => 'lib\Predis\Command\StringSetMultiplePreserve',
            'GET'                       => 'lib\Predis\Command\StringGet',
            'MGET'                      => 'lib\Predis\Command\StringGetMultiple',
            'GETSET'                    => 'lib\Predis\Command\StringGetSet',
            'INCR'                      => 'lib\Predis\Command\StringIncrement',
            'INCRBY'                    => 'lib\Predis\Command\StringIncrementBy',
            'DECR'                      => 'lib\Predis\Command\StringDecrement',
            'DECRBY'                    => 'lib\Predis\Command\StringDecrementBy',

            /* commands operating on lists */
            'RPUSH'                     => 'lib\Predis\Command\ListPushTail',
            'LPUSH'                     => 'lib\Predis\Command\ListPushHead',
            'LLEN'                      => 'lib\Predis\Command\ListLength',
            'LRANGE'                    => 'lib\Predis\Command\ListRange',
            'LTRIM'                     => 'lib\Predis\Command\ListTrim',
            'LINDEX'                    => 'lib\Predis\Command\ListIndex',
            'LSET'                      => 'lib\Predis\Command\ListSet',
            'LREM'                      => 'lib\Predis\Command\ListRemove',
            'LPOP'                      => 'lib\Predis\Command\ListPopFirst',
            'RPOP'                      => 'lib\Predis\Command\ListPopLast',
            'RPOPLPUSH'                 => 'lib\Predis\Command\ListPopLastPushHead',

            /* commands operating on sets */
            'SADD'                      => 'lib\Predis\Command\SetAdd',
            'SREM'                      => 'lib\Predis\Command\SetRemove',
            'SPOP'                      => 'lib\Predis\Command\SetPop',
            'SMOVE'                     => 'lib\Predis\Command\SetMove',
            'SCARD'                     => 'lib\Predis\Command\SetCardinality',
            'SISMEMBER'                 => 'lib\Predis\Command\SetIsMember',
            'SINTER'                    => 'lib\Predis\Command\SetIntersection',
            'SINTERSTORE'               => 'lib\Predis\Command\SetIntersectionStore',
            'SUNION'                    => 'lib\Predis\Command\SetUnion',
            'SUNIONSTORE'               => 'lib\Predis\Command\SetUnionStore',
            'SDIFF'                     => 'lib\Predis\Command\SetDifference',
            'SDIFFSTORE'                => 'lib\Predis\Command\SetDifferenceStore',
            'SMEMBERS'                  => 'lib\Predis\Command\SetMembers',
            'SRANDMEMBER'               => 'lib\Predis\Command\SetRandomMember',

            /* commands operating on sorted sets */
            'ZADD'                      => 'lib\Predis\Command\ZSetAdd',
            'ZINCRBY'                   => 'lib\Predis\Command\ZSetIncrementBy',
            'ZREM'                      => 'lib\Predis\Command\ZSetRemove',
            'ZRANGE'                    => 'lib\Predis\Command\ZSetRange',
            'ZREVRANGE'                 => 'lib\Predis\Command\ZSetReverseRange',
            'ZRANGEBYSCORE'             => 'lib\Predis\Command\ZSetRangeByScore',
            'ZCARD'                     => 'lib\Predis\Command\ZSetCardinality',
            'ZSCORE'                    => 'lib\Predis\Command\ZSetScore',
            'ZREMRANGEBYSCORE'          => 'lib\Predis\Command\ZSetRemoveRangeByScore',

            /* connection related commands */
            'PING'                      => 'lib\Predis\Command\ConnectionPing',
            'AUTH'                      => 'lib\Predis\Command\ConnectionAuth',
            'SELECT'                    => 'lib\Predis\Command\ConnectionSelect',
            'ECHO'                      => 'lib\Predis\Command\ConnectionEcho',
            'QUIT'                      => 'lib\Predis\Command\ConnectionQuit',

            /* remote server control commands */
            'INFO'                      => 'lib\Predis\Command\ServerInfoV26x',
            'SLAVEOF'                   => 'lib\Predis\Command\ServerSlaveOf',
            'MONITOR'                   => 'lib\Predis\Command\ServerMonitor',
            'DBSIZE'                    => 'lib\Predis\Command\ServerDatabaseSize',
            'FLUSHDB'                   => 'lib\Predis\Command\ServerFlushDatabase',
            'FLUSHALL'                  => 'lib\Predis\Command\ServerFlushAll',
            'SAVE'                      => 'lib\Predis\Command\ServerSave',
            'BGSAVE'                    => 'lib\Predis\Command\ServerBackgroundSave',
            'LASTSAVE'                  => 'lib\Predis\Command\ServerLastSave',
            'SHUTDOWN'                  => 'lib\Predis\Command\ServerShutdown',
            'BGREWRITEAOF'              => 'lib\Predis\Command\ServerBackgroundRewriteAOF',

            /* ---------------- Redis 2.0 ---------------- */

            /* commands operating on string values */
            'SETEX'                     => 'lib\Predis\Command\StringSetExpire',
            'APPEND'                    => 'lib\Predis\Command\StringAppend',
            'SUBSTR'                    => 'lib\Predis\Command\StringSubstr',

            /* commands operating on lists */
            'BLPOP'                     => 'lib\Predis\Command\ListPopFirstBlocking',
            'BRPOP'                     => 'lib\Predis\Command\ListPopLastBlocking',

            /* commands operating on sorted sets */
            'ZUNIONSTORE'               => 'lib\Predis\Command\ZSetUnionStore',
            'ZINTERSTORE'               => 'lib\Predis\Command\ZSetIntersectionStore',
            'ZCOUNT'                    => 'lib\Predis\Command\ZSetCount',
            'ZRANK'                     => 'lib\Predis\Command\ZSetRank',
            'ZREVRANK'                  => 'lib\Predis\Command\ZSetReverseRank',
            'ZREMRANGEBYRANK'           => 'lib\Predis\Command\ZSetRemoveRangeByRank',

            /* commands operating on hashes */
            'HSET'                      => 'lib\Predis\Command\HashSet',
            'HSETNX'                    => 'lib\Predis\Command\HashSetPreserve',
            'HMSET'                     => 'lib\Predis\Command\HashSetMultiple',
            'HINCRBY'                   => 'lib\Predis\Command\HashIncrementBy',
            'HGET'                      => 'lib\Predis\Command\HashGet',
            'HMGET'                     => 'lib\Predis\Command\HashGetMultiple',
            'HDEL'                      => 'lib\Predis\Command\HashDelete',
            'HEXISTS'                   => 'lib\Predis\Command\HashExists',
            'HLEN'                      => 'lib\Predis\Command\HashLength',
            'HKEYS'                     => 'lib\Predis\Command\HashKeys',
            'HVALS'                     => 'lib\Predis\Command\HashValues',
            'HGETALL'                   => 'lib\Predis\Command\HashGetAll',

            /* transactions */
            'MULTI'                     => 'lib\Predis\Command\TransactionMulti',
            'EXEC'                      => 'lib\Predis\Command\TransactionExec',
            'DISCARD'                   => 'lib\Predis\Command\TransactionDiscard',

            /* publish - subscribe */
            'SUBSCRIBE'                 => 'lib\Predis\Command\PubSubSubscribe',
            'UNSUBSCRIBE'               => 'lib\Predis\Command\PubSubUnsubscribe',
            'PSUBSCRIBE'                => 'lib\Predis\Command\PubSubSubscribeByPattern',
            'PUNSUBSCRIBE'              => 'lib\Predis\Command\PubSubUnsubscribeByPattern',
            'PUBLISH'                   => 'lib\Predis\Command\PubSubPublish',

            /* remote server control commands */
            'CONFIG'                    => 'lib\Predis\Command\ServerConfig',

            /* ---------------- Redis 2.2 ---------------- */

            /* commands operating on the key space */
            'PERSIST'                   => 'lib\Predis\Command\KeyPersist',

            /* commands operating on string values */
            'STRLEN'                    => 'lib\Predis\Command\StringStrlen',
            'SETRANGE'                  => 'lib\Predis\Command\StringSetRange',
            'GETRANGE'                  => 'lib\Predis\Command\StringGetRange',
            'SETBIT'                    => 'lib\Predis\Command\StringSetBit',
            'GETBIT'                    => 'lib\Predis\Command\StringGetBit',

            /* commands operating on lists */
            'RPUSHX'                    => 'lib\Predis\Command\ListPushTailX',
            'LPUSHX'                    => 'lib\Predis\Command\ListPushHeadX',
            'LINSERT'                   => 'lib\Predis\Command\ListInsert',
            'BRPOPLPUSH'                => 'lib\Predis\Command\ListPopLastPushHeadBlocking',

            /* commands operating on sorted sets */
            'ZREVRANGEBYSCORE'          => 'lib\Predis\Command\ZSetReverseRangeByScore',

            /* transactions */
            'WATCH'                     => 'lib\Predis\Command\TransactionWatch',
            'UNWATCH'                   => 'lib\Predis\Command\TransactionUnwatch',

            /* remote server control commands */
            'OBJECT'                    => 'lib\Predis\Command\ServerObject',
            'SLOWLOG'                   => 'lib\Predis\Command\ServerSlowlog',

            /* ---------------- Redis 2.4 ---------------- */

            /* remote server control commands */
            'CLIENT'                    => 'lib\Predis\Command\ServerClient',

            /* ---------------- Redis 2.6 ---------------- */

            /* commands operating on the key space */
            'PTTL'                      => 'lib\Predis\Command\KeyPreciseTimeToLive',
            'PEXPIRE'                   => 'lib\Predis\Command\KeyPreciseExpire',
            'PEXPIREAT'                 => 'lib\Predis\Command\KeyPreciseExpireAt',

            /* commands operating on string values */
            'PSETEX'                    => 'lib\Predis\Command\StringPreciseSetExpire',
            'INCRBYFLOAT'               => 'lib\Predis\Command\StringIncrementByFloat',
            'BITOP'                     => 'lib\Predis\Command\StringBitOp',
            'BITCOUNT'                  => 'lib\Predis\Command\StringBitCount',

            /* commands operating on hashes */
            'HINCRBYFLOAT'              => 'lib\Predis\Command\HashIncrementByFloat',

            /* scripting */
            'EVAL'                      => 'lib\Predis\Command\ServerEval',
            'EVALSHA'                   => 'lib\Predis\Command\ServerEvalSHA',
            'SCRIPT'                    => 'lib\Predis\Command\ServerScript',

            /* remote server control commands */
            'TIME'                      => 'lib\Predis\Command\ServerTime',
            'SENTINEL'                  => 'lib\Predis\Command\ServerSentinel',
        );
    }
}
