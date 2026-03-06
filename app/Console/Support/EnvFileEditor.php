<?php

namespace App\Console\Support;

class EnvFileEditor
{
    protected string $contents;

    protected string $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path ?? base_path('.env');
        $this->contents = file_exists($this->path) ? file_get_contents($this->path) : '';
    }

    public function get(string $key, string $default = ''): string
    {
        if (preg_match("/^{$key}=(.*)$/m", $this->contents, $matches)) {
            $value = trim($matches[1]);

            if (preg_match('/^"(.*)"$/', $value, $quoted)) {
                return $quoted[1];
            }

            if (preg_match("/^'(.*)'$/", $value, $quoted)) {
                return $quoted[1];
            }

            return $value;
        }

        return $default;
    }

    public function set(string $key, string $value): self
    {
        $escaped = $this->escapeValue($value);

        if (preg_match("/^{$key}=.*$/m", $this->contents)) {
            $this->contents = preg_replace(
                "/^{$key}=.*$/m",
                "{$key}={$escaped}",
                $this->contents
            );
        } else {
            $this->contents = rtrim($this->contents)."\n{$key}={$escaped}\n";
        }

        return $this;
    }

    public function save(): void
    {
        file_put_contents($this->path, $this->contents);
    }

    protected function escapeValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\s#]/', $value) || str_contains($value, '${')) {
            return '"'.addcslashes($value, '"').'"';
        }

        return $value;
    }
}
