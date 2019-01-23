<?php

namespace Circli\WebCore;

class PathContainer implements \Circli\Contracts\PathContainer
{
    private $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function getConfigPath(): string
    {
        return $this->basePath . '/config/';
    }

    public function getTemplatePath(): string
    {
        return $this->basePath . '/templates/';
    }

    public function getExtensionPath(): string
    {
        return $this->basePath . '/extensions/';
    }

    public function getConfigFile(string $file): string
    {
        if (substr($file, -4) !== '.php') {
            $file .= '.php';
        }

        //todo add error checking for file exists
        return $this->getConfigPath().$file;
    }

    public function loadConfigFile(string $file): array
    {
        return (array)include $this->getConfigFile($file);
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}