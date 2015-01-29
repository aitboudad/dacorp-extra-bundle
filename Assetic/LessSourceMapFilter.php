<?php

namespace Dacorp\ExtraBundle\Assetic;

use Assetic\Filter\LessFilter;
use Assetic\Asset\AssetInterface;

class LessSourceMapFilter extends LessFilter
{
    public function filterLoad(AssetInterface $asset)
    {
        $sourcemapRoot = realpath(dirname($asset->getSourceRoot().'/'.$asset->getSourcePath()));

        $this->addTreeOption('sourceMap', true);
        $this->addTreeOption('sourceMapBasepath', $sourcemapRoot);

        parent::filterLoad($asset);
    }
}
