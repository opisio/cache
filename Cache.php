<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Cache;

use Closure;
use Opis\Cache\CacheStorage;

class Cache
{
    
    protected $storage;
    
    protected static $instances = array();
    
    public function __construct(CacheStorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->storage, $name), $arguments);
    }
    
    final public function load($key, Closure $closure, $ttl = 0)
    {
        if($this->storage->has($key))
        {
            return $this->storage->read($key);
        }
        $value = $closure();
        $this->storage->write($key, $value, $ttl);
        return $value;
    }
    
    public static function get($name = null)
    {
        if($name == null)
        {
            $name = CacheStorage::getDefaultStorage();
        }
        if(!isset(self::$instances[$name]))
        {
            self::$instances[$name] = new Cache(CacheStorage::build($name));
        }
        return self::$instances[$name];
    }
    
}
