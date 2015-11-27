<?php

/**
 * @link http://www.d-bog.com
 *
 * @license  GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Joosco\Component\Site;

defined('_JEXEC') or die('Restricted access');

use JControllerBase;
use SplPriorityQueue;

/**
 * Joosco Component Controller.
 */
class Controller extends JControllerBase
{
    protected $app;

    protected $viewName;

    protected $paths;

    protected $viewFormat;

    protected $viewClass;

    public function __construct()
    {
        parent::__construct();
        self::setViewName();
        // Register the layout paths for the view
        self::registerPaths();
        self::setViewFormat();
        self::createClassView();
    }

    private function setViewName()
    {
        $this->viewName = $this->app->input->get('view', 'display');
        $this->app->input->set('view', $this->viewName);
    }

    private function registerPaths()
    {
        $path = JPATH_COMPONENT.'/Views/default/tmpl';
        $this->paths = new SplPriorityQueue();
        $this->paths->insert($path, 'normal');
    }

    private function setViewFormat()
    {
        $format = $this->app->input->get('format', 'html');

        $this->viewFormat = $format;
    }

    private function createClassView()
    {
        $this->viewClass = __NAMESPACE__.'\Views\\'.ucfirst($this->viewName);
    }

    /**
     * Method to execute the view.
     */
    public function execute()
    {
        $this->app = $this->getApplication();

        $layout = self::createLayout();

        $layout->render();

        return true;
    }

    protected function createLayout()
    {
        #FIXME: The class may not exist.
        $view = new $this->viewClass($this->paths);
        $view->setLayout($this->viewName);

        return $view;
    }
}
