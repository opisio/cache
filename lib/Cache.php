<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2014 Marius Sarca
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

class Cache implements StorageInterface
{
    
    protected $storage;
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->storage, $name), $arguments);
    }
    
    public function read($key)
    {
        return $this->storage->read($key);
    }
  
    public function write($key, $value, $ttl = 0)
    {
        return $this->storage->write($key, $value, $ttl);
    }
  
    public function delete($key)
    {
        return $this->storage->delete($key);
    }
  
    public function has($key)
    {
        return $this->storage->has($key);
    }
  
    public function clear()
    {
        return $this->storage->clear();
    }
    
    public function load($key, Closure $closure, $ttl = 0)
    {
        if($this->storage->has($key))
        {
            return $this->storage->read($key);
        }
        $value = $closure();
        $this->storage->write($key, $value, $ttl);
        return $value;
    }
    
}
