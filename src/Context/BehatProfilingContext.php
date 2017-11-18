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
    /**
     * @BeforeSuite
     */
    public static function onSuiteStart(BeforeSuiteScope $scope)
    {
        // Load the configuration
        // Prepare the profiler
        ProfilerFactory::setProfiler(new DefaultProfiler());

        ProfilerFactory::getProfiler()->start(Action::SUITE, $scope->getName());
    }

    /**
     * @AfterSuite
     */
    public function onSuiteStop(AfterSuiteScope $scope)
    {
        $result = $scope->getTestResult();
        ProfilerFactory::getProfiler()->stop(Action::SUITE, $scope->getName(), $result->isPassed(), $result->getResultCode());
        ProfilerFactory::getProfiler()->stopAll(false, '', 'Test suite stopped');

        // Pass completed actions to the formatter
    }

    /**
     * @BeforeFeature
     */
    public function onFeatureStart(BeforeFeatureScope $scope)
    {
        ProfilerFactory::getProfiler()->start(Action::FEATURE, $scope->getName());
    }

    /**
     * @AfterFeature
     */
    public function onFeatureStop(AfterFeatureScope $scope)
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