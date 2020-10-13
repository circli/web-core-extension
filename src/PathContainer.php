<?php declare(strict_types=1);

namespace Circli\WebCore;

class PathContainer implements \Circli\Contracts\PathContainer
{
    private string $basePath;

    public function __construct(string $basePath)
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

        return $this->getConfigPath() . $file;
    }

    public function loadConfigFile(string $file): array
    {
        $path = $this->getConfigFile($file);
        if (file_exists($path)) {
            return (array) include $path;
        }
        return [];
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
