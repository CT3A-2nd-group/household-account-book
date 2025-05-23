<?php
/**
 * controllers/ 以下を深さ無制限で走査し、
 * 「クラス名.php」が見つかれば require_once するだけ。
 */
spl_autoload_register(function ($class) {
    $base = __DIR__ . '/../controllers';
    $it   = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
            );
    foreach ($it as $file) {
        if ($file->isFile() && $file->getFilename() === "$class.php") {
            require_once $file->getPathname();
            return;
        }
    }
});
