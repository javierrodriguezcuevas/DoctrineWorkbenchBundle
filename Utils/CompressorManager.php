<?php

namespace Mst\DoctrineWorkbenchBundle\Utils;

use Webmozart\Assert\Assert;

/**
 * @author javi
 */
class CompressorManager 
{       
    /**
     * Generate a zip file
     * 
     * @param string $dir      dir with files to zip
     * @param string $filename name for zip file
     * 
     * @return \ZipArchive or false
     */
    public function generateZip($dir, $filename)
    {   
        Assert::stringNotEmpty($dir);
        Assert::stringNotEmpty($filename);
        Assert::directory($dir);

        $zip = new \ZipArchive();
        
        $openResult = $zip->open($dir . DIRECTORY_SEPARATOR . $filename, \ZipArchive::CREATE);
        
        Assert::true($openResult);
                   
        foreach (glob($dir . DIRECTORY_SEPARATOR . "*.*") as $file) {
            Assert::true($zip->addFile($file, basename($file)));
        }
        
        $zip->close();
        
        return $zip;
    }
}
