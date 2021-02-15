<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Common\Utils;
use Hyperf\Redis\RedisFactory;
use App\Common\Log;

class IndexController extends AbstractController
{
    public function index()
    {
        $id = Utils::short(Utils::getUniqueId());
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'id' => $id,
        ];
    }

    public function test()
    {
        $all = $this->request->all();
        $method = $this->request->getMethod();
        $id = $all['params']['id'];
        // Log::info($id, ['name' => 'tanliang']);
        $redis = $this->container->get(RedisFactory::class)->get('default');

        return [
            'method' => $method,
            'success' => $redis->EXISTS($id),
        ];
    }

    public function sync()
    {
        $all = $this->request->all();
        $method = $this->request->getMethod();
        $id = $all['params']['id'];
        $text = '';
        if (isset($all['params']['text'])) {
            $text = $all['params']['text'];
        }

        $redis = $this->container->get(RedisFactory::class)->get('default');
        
        if ($text || (!$text && !$redis->EXISTS($id))) {
            $redis->SET($id, $text, 1200);
        } else {
            $text = $redis->GET($id);
        }

        return [
            'method' => $method,
            'text' => $text,
        ];  
    }
}
