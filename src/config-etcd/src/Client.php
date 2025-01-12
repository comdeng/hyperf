<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\ConfigEtcd;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Etcd\KVInterface;

class Client implements ClientInterface
{
    /**
     * @var KVInterface
     */
    protected $client;

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(KVInterface $client, ConfigInterface $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function pull(): array
    {
        $namespaces = $this->config->get('config_etcd.namespaces');
        $kvs = [];
        foreach ($namespaces as $namespace) {
            $res = $this->client->fetchByPrefix($namespace);
            if (isset($res['kvs'])) {
                foreach ($res['kvs'] as $kv) {
                    $kvs[$kv['key']] = $kv;
                }
            }
        }

        return $kvs;
    }
}
