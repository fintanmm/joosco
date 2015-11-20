<?php

/**
 * @author Fintan MacMahon
 * @copyright   Copyright (C) 2015 Fintan MacMahon
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Joosco\UnitTests\Helpers;

use TestCaseDatabase;
use JFactory;
use TestReflection;
use SplPriorityQueue;

class TestCase extends TestCaseDatabase
{
    public function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        $this->app = JFactory::$application = $this->getMockCmsApp();
        $this->input = JFactory::$application->input;

        $this->backupServer = $_SERVER;

        $this->joomlaReflection = new TestReflection();

        $this->testConfig = [1 => 'Test'];
        self::resetInputArray();
    }

    /**
     * @return SplPriorityQueue A prioritized queue containing the paths.
     */
    public function viewPathHelper($path = 'Site/Views/default/tmpl')
    {
        $path = JPATH_COMPONENT.$path;
        $paths = new SplPriorityQueue();
        $paths->insert($path, 1);

        return $paths;
    }

    public function resetInputArray()
    {
        $inputArray = $this->input->getArray();
        foreach ($inputArray as $key) {
            $this->input->set($key, '');
        }
    }

    public function setLanguageHelper($path = 'Site')
    {
        $component = 'com_joosco';
        $baseDir = JPATH_COMPONENT.$path;
        $paths = ['com_joosco' => [$baseDir.'/language/en-GB/en-GB.com_joosco.ini' => true]];
        $language = JFactory::getLanguage();
        $this->joomlaReflection->setValue($language, 'paths', $paths);

        $languageTag = $language->getTag();
        $language->load($component, $baseDir, $languageTag, true);
    }

    public function tearDown()
    {
    }
}
