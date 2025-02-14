<?php

namespace warm\admin\support\cores;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use warm\admin\support\Helper;

/**
 * Class Context.
 *
 * @property array $apis
 */
class Context extends Fluent
{
    public function set($key, $value = null): static
    {
        $data = is_array($key) ? $key : [$key => $value];

        foreach ($data as $key => $value) {
            Arr::set($this->attributes, $key, $value);
        }

        return $this;
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    public function remember($key, \Closure $callback)
    {
        if (($value = $this->get($key)) !== null) {
            return $value;
        }

        return tap($callback(), function ($value) use ($key) {
            $this->set($key, $value);
        });
    }

    public function getArray($key, $default = null): array
    {
        return Helper::array($this->get($key, $default), false);
    }

    public function add($key, $value, $k = null): static
    {
        $results = $this->getArray($key);

        if ($k === null) {
            $results[] = $value;
        } else {
            $results[$k] = $value;
        }

        return $this->set($key, $results);
    }

    public function merge($key, array $value): static
    {
        $results = $this->getArray($key);

        return $this->set($key, array_merge($results, $value));
    }

    public function forget($keys): void
    {
        Arr::forget($this->attributes, $keys);
    }

    public function flush(): void
    {
        $this->attributes = [];
    }
}
