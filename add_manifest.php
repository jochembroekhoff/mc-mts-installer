<?php

/*
 * Add the manifest from the 'jar without dependencies'
 * to the 'jar with dependencies'.
 * Do the same with some files in META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer
 * @author Jochem Broekhoff
 */

//Find version in POM.xml
echo "[DETECT-VERSION] Reading POM.xml\n";
$pom = simplexml_load_file(dirname(__FILE__) . '/pom.xml');
$pom_json = json_encode($pom);
$pom_obj = json_decode($pom_json);
$version = $pom_obj->version;
echo "[DETECT-VERSION] Version found is $version\n";

//Set files
$file_without = dirname(__FILE__) . '/target/MC_MTS_Installer-' . $version . '-jar-with-dependencies.jar';
$file_with = dirname(__FILE__) . '/target/MC_MTS_Installer-' . $version . '.jar';

echo "[CHECK-FILES]\t Checking files...\n";
if(file_exists($file_without) && file_exists($file_with)) {
    echo "[CHECK-FILES]\t All files exists\n";
    $without = new ZipArchive;
    $with = new ZipArchive;

    echo "[OPEN-JARS]\t Opening jars...\n";
    $without->open($file_without);
    $with->open($file_with);
    echo "[OPEN-JARS]\t Opened jars\n";

    echo "[LOAD-FILES]\t Loading files...\n";
    $manifest = $with->getFromName('META-INF/MANIFEST.MF');
    $pom_properties = $with->getFromName('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.properties');
    $pom_xml = $with->getFromName('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.xml');
    echo "[LOAD-FILES]\t Files loaded\n";

    echo "[ADD-FILES]\t Adding files...\n";
    $without->deleteName('META-INF/MANIFEST.MF');
    $without->addFromString('META-INF/MANIFEST.MF', $manifest);
    echo "[ADD-FILES]\t MANIFEST.MF added\n";
    $without->deleteName('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.properties');
    $without->addFromString('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.properties', $pom_properties);
    echo "[ADD-FILES]\t pom.properties added\n";
    $without->deleteName('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.xml');
    $without->addFromString('META-INF/maven/nl.jochembroekhoff/MC_MTS_Installer/pom.xml', $pom_xml);
    echo "[ADD-FILES]\t pom.xml added\n";
    
    die("[ADD-FILES]\t All files are added: Done");
} else {
    die("This version doesn't exist\n");
}