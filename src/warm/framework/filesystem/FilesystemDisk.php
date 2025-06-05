<?php

namespace warm\framework\filesystem;

use League\Flysystem\FilesystemOperator;

class FilesystemDisk
{
    protected FilesystemOperator $filesystem;
    protected array $config;

    public function __construct(FilesystemOperator $filesystem, array $config)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function __call($method, $parameters)
    {
        return $this->filesystem->$method(...$parameters);
    }
}