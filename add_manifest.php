<?php

/*
 * Add the manifest from the 'jar without dependencies'
 * to the 'jar with dependencies'
 * @author Jochem Broekhoff
 */

if(isset($argv[1])) {
    $version = $argv[1];
    $file_without = dirname(__FILE__) . '/target/MC_MTS_Installer-' . $version . '-jar-with-dependencies.jar';
    $file_with = dirname(__FILE__) . '/target/MC_MTS_Installer-' . $version . '.jar';
    
    if(file_exists($file_without) && file_exists($file_with)) {
        $without = new ZipArchive;
        $with = new ZipArchive;

        $without->open($file_without);
        $with->open($file_with);

        $manifest = $with->getFromName('META-INF/MANIFEST.MF');

        $without->deleteName('META-INF/MANIFEST.MF');
        $without->addFromString('META-INF/MANIFEST.MF', $manifest);
    } else {
        die("This version doesn't exist");
    }
} else {
    die("Add version argument!");
}