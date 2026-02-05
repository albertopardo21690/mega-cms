<?php

namespace App\Core\Hooks;

class HookManager
{
    /** @var array<string, array<int, array<int, callable>>> */
    private array $actions = [];

    /** @var array<string, array<int, array<int, callable>>> */
    private array $filters = [];

    public function addAction(string $hook, callable $cb, int $priority = 10): void
    {
        $this->actions[$hook][$priority][] = $cb;
    }

    public function doAction(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook])) return;

        ksort($this->actions[$hook]);
        foreach ($this->actions[$hook] as $cbs) {
            foreach ($cbs as $cb) $cb(...$args);
        }
    }

    public function addFilter(string $hook, callable $cb, int $priority = 10): void
    {
        $this->filters[$hook][$priority][] = $cb;
    }

    public function applyFilters(string $hook, $value, ...$args)
    {
        if (!isset($this->filters[$hook])) return $value;

        ksort($this->filters[$hook]);
        foreach ($this->filters[$hook] as $cbs) {
            foreach ($cbs as $cb) $value = $cb($value, ...$args);
        }
        return $value;
    }
}
