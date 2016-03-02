<?php

/**
 * @author: Fintan MacMahon
 *
 * @copyright   Copyright (C) 2015 Fintan MacMahon
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Joosco\Component\Site\Views;

use JViewHtml;
use JFactory;

/**
 * Default view class.
 */
class Documents extends JViewHtml
{
    protected $app;

    public function __construct($paths)
    {
        // Setup dependencies.
        $this->paths = isset($paths) ? $paths : $this->loadPaths();
        $this->app = JFactory::getApplication();
    }

    public function render()
    {
        $render = parent::render();
        echo $render;
    }
    
    public function getUrl($node)
    {
        return $node;
    }
    
    public function getImageURL($current_type = '{http://www.alfresco.org/model/content/1.0}folder')
    {
        $result = 'space_small.gif';
        if ($current_type == '{http://www.alfresco.org/model/content/1.0}content') {
            $result = 'post.gif';
        } 

        return $result;
    }
}
