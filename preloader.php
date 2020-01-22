<?php

class CW_Preload {
    private static int $count = 0;

    private array $paths_ignored = [];
    private array $paths;

    public function __construct(string ...$paths)
    {
        $this->paths = $paths;
    }

    public function paths(string ...$paths): CW_Preload
    {
        $this->paths = array_merge(
            $this->paths,
            $paths
        );

        return $this;
    }

    public function ignores(string ...$paths): CW_Preload
    {
        $this->paths_ignored = array_merge(
            $this->paths_ignored,
            $paths
        );

        return $this;
    }

    public function load(): void
    {
        echo "loading";
        // We'll loop over all registered paths
        // and load them one by one
        foreach ($this->paths as $path) {
            $this->loadPath(rtrim($path, '/'));
        }

        $count = self::$count;

        echo "[Preloader] Preloaded {$count} classes" . PHP_EOL;
    }

    private function loadPath(string $path): void
    {
        // If the current path is a directory,
        // we'll load all files in it
        if (is_dir($path)) {
            $this->loadDir($path);

            return;
        }

        if (strpos($path,'.php') === false)
            return;

        if ($this->checkIgnore($path)) {
            echo "[Preloader] IGNORED `{$path}`" . PHP_EOL;
            return;
        }

        // Otherwise we'll just load this one file
        $this->loadFile($path);
    }

    private function loadDir(string $path): void
    {
        $handle = opendir($path);

        // We'll loop over all files and directories
        // in the current path,
        // and load them one by one
        while ($file = readdir($handle)) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $this->loadPath("{$path}/{$file}");
        }

        closedir($handle);
    }

    private function loadFile(string $path): void
    {
        if (!opcache_compile_file($path)) {
            echo "[Preloader FAILED] `{$path}`" . PHP_EOL;
            return;
        }


        self::$count++;

        echo "[Preloader] Preloaded `{$path}`" . PHP_EOL;
    }

    public function checkIgnore($path) {
        foreach ($this->paths_ignored as $item) {
            if (strpos($path,$item) !== false) {
                return true;
            }
        }
        return false;
    }

}
