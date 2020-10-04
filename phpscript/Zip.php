<?php
    function Zip($path, $zippath)
    {
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zippath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        if(!is_array($path))
        {
            $path = array($path);
        }
        foreach($path as $p)
        {
            if(is_file($p))
            {
                $content = file_get_contents($p);
                $zip->addFromString(pathinfo($p, PATHINFO_BASENAME), $content);
                continue;
            }
            $rootPath = $p;
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = basename($rootPath)."/".substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        

        // Zip archive will be created only after closing object
        $zip->close();
    }
?>
