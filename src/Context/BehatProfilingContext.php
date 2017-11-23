<?php
namespace BehatProfiling\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use BehatProfiling\Formatter\JMeterCsvFormatter;
use BehatProfiling\Profiler\Action;
use BehatProfiling\Profiler\DefaultProfiler;
use BehatProfiling\Profiler\EmptyProfiler;
use BehatProfiling\Profiler\ProfilerFactory;
use BehatProfiling\Profiler\ProfilerInterface;

/**
 * Class BehatProfilingContext
 * @package BehatLoadTest\Context
 * @author Christian Labonté
 */
class BehatProfilingContext implements Context
{
    /** @var string */
    static private $filename;
    /** @var string */
    static private $group;
    /** @var string */
    static private $configFile;

    public function __construct(array $parameters)
    {
        if (isset($parameters['filename'])) {
            self::$filename = $parameters['filename'];
        } else {
            self::$filename = 'result.csv';
        }
        if (isset($parameters['group'])) {
            self::$group = $parameters['group'];
        } else {
            self::$group = '';
        }
        if (isset($parameters['configFile'])) {
            self::$configFile = $parameters['configFile'];
        }
    }

    /**
     * @BeforeSuite
     */
    public static function onSuiteStart(BeforeSuiteScope $scope)
    {
        // Load the configuration
        // Prepare the profiler
        $profiler = new DefaultProfiler();
        if (!empty(self::$configFile)) {
            $profiler->loadConfigFile(self::$configFile);
        }
        ProfilerFactory::setProfiler($profiler);

        ProfilerFactory::getProfiler()->start(Action::SUITE, $scope->getName());
    }

    /**
     * @AfterSuite
     */
    public static function onSuiteStop(AfterSuiteScope $scope)
    {
        $result = $scope->getTestResult();
        ProfilerFactory::getProfiler()->stop(Action::SUITE, $scope->getName(), $result->isPassed(), $result->getResultCode());
        ProfilerFactory::getProfiler()->stopAll(false, '', 'Test suite stopped');

        // Pass completed actions to the formatter
        $actions = ProfilerFactory::getProfiler()->listCompletedActions();
        $formatter = new JMeterCsvFormatter(self::$filename);
        $formatter->formatProfilerActions($actions, self::$group);
        $formatter->close();
    }

    /**
     * @BeforeFeature
     */
    public static function onFeatureStart(BeforeFeatureScope $scope)
    {
        ProfilerFactory::getProfiler()->start(Action::FEATURE, $scope->getName());
    }

    /**
     * @AfterFeature
     */
    public static function onFeatureStop(AfterFeatureScope $scope)
    {
        $result = $scope->getTestResult();
        ProfilerFactory::getProfiler()->stop(Action::FEATURE, $scope->getName(), $result->isPassed(), $result->getResultCode());
    }

    /**
     * @BeforeScenario
     */
    public function onScenarioStart(BeforeScenarioScope $scope)
    {
        ProfilerFactory::getProfiler()->start(Action::SCENARIO, $scope->getName());
    }

    /**
     * @AfterScenario
     */
    public function onScenarioStop(AfterScenarioScope $scope)
    {
        $result = $scope->getTestResult();
        ProfilerFactory::getProfiler()->stop(Action::SCENARIO, $scope->getName(), $result->isPassed(), $result->getResultCode());
    }

    /**
     * @BeforeStep
     */
    public function onStepStart(BeforeStepScope $scope)
    {
        ProfilerFactory::getProfiler()->start(Action::STEP, $scope->getName());
    }

    /**
     * @AfterStep
     */
    public function onStepStop(AfterStepScope $scope)
    {
        $result = $scope->getTestResult();
        ProfilerFactory::getProfiler()->stop(Action::STEP, $scope->getName(), $result->isPassed(), $result->getResultCode());
    }

}