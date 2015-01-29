<?php

/*
 * This file is part of the Dacorp Extra Bundle
 *
 * (c) Jean-Christophe Meillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dacorp\ExtraBundle\Services;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class YmlFileManager
{

    protected $rootDir;

    /**
     * @return mixed
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Load a yml file and return an array of the data ordered by keys
     * @param  string $fileName
     * @param  string $path     (full path to search in)
     * @return array
     */
    public function loadYmlFile($fileName, $path)
    {
        $configDirectories = array(($path != null) ? $path : $this->rootDir);

        $locator = new FileLocator($configDirectories);
        $ymlFile = $locator->locate($fileName, null, true);
        $ymlDatas = Yaml::parse($ymlFile);

        $dataArray = array();
        foreach ($ymlDatas as $key => $data) {
            $dataArray[$key] = array_merge(
                $data);
        }

        return $dataArray;
    }
}
