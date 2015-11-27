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
class Display extends JViewHtml
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
}
