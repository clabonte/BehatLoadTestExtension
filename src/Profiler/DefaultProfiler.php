<?php
namespace BehatProfiling\Profiler;

/**
 * Class DefaultProfiler
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
class DefaultProfiler implements ProfilerInterface
{
    /** @var array */
    private $currentActions;
    /** @var ProfilerAction[] */
    private $completedActions;
    /** @var array */
    private $config = [];

    /**
     * DefaultProfiler constructor.
     */
    public function __construct()
    {
        $this->currentActions = array();
        $this->completedActions = array();
    }


    /**
     * This method is called to start the profiling of an action.
     *
     * @param string $action The action type
     * @param string $label The name of the action to profile
     *
     * @see Action
     */
    public function start($action, $label)
    {
        if (!isset($this->currentActions[$action])) {
            $this->currentActions[$action] = array();
        }

        $this->currentActions[$action][$label] = new ProfilerAction($action, $label, $this->getTimestamp());
    }

    /**
     * Shortcut to start(Action::USER, $label)
     */
    public function userStart($label)
    {
        $this->start(Action::USER, $label);
    }

    /**
     * Shortcut to start(Action::API, $label)
     */
    public function apiStart($label)
    {
        $this->start(Action::API, $label);
    }

    /**
     * Shortcut to start(Action::PROCESS, $label)
     */
    public function processStart($label)
    {
        $this->start(Action::PROCESS, $label);
    }

    /**
     * This method is called to stop the profiling of an action. If the action was not previously started, the method
     * silently ignores it.
     *
     * @param string $action The action type
     * @param string $label The name of the action to profile
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $responseCode The response code to associate with this action, if any
     * @param string $message The message to associate with this action, if any
     * @param string|array $data data to associate with this action, if any
     * @param float $timestamp The time at which the action was stopped. If null, this is set by the profiler
     *
     * @see Action
     */
    public function stop($action, $label, $success = true, $responseCode = '', $message = '', $data = null, $timestamp = null)
    {
        if ($timestamp == null) {
            $timestamp = $this->getTimestamp();
        }

        if (isset($this->currentActions[$action][$label])) {
            /** @var ProfilerAction $profileAction */
            $profileAction = $this->currentActions[$action][$label];
            unset ($this->currentActions[$action][$label]);

            // Check if we need to do some analysis on this action...
            if (isset($this->config[$action][$label])) {
                // Check if we have been asked to override 'success' based on the response code
                if (isset($this->config[$action][$label][$responseCode]) && ($success != $this->config[$action][$label][$responseCode])) {
                    $success = $this->config[$action][$label][$responseCode];
                    $message = 'Result overriden as per config. '.$message;
                }

                // Check if we need to override 'success' based on the delay
                if (isset($this->config[$action][$label]['max_delay'])) {
                    if ( ($timestamp - $profileAction->getStartTime() > $this->config[$action][$label]['max_delay']) && $success) {
                        $message = 'Delay greater than '.$this->config[$action][$label]['max_delay']. '. '.$message;
                        $success = false;
                    }
                }
            }

            if (isset($this->config[$action]['max_delay']) && !isset($this->config[$action][$label]['max_delay']) && $success) {
                if ( ($timestamp - $profileAction->getStartTime() > $this->config[$action][$label]['max_delay']) && $success) {
                    $message = 'Delay for '.$action.' greater than '.$this->config[$action][$label]['max_delay']. '. '.$message;
                    $success = false;
                }
            }

            $profileAction->close($timestamp, $success, $responseCode, $message, $data);
            $this->completedActions[] = $profileAction;
        }
    }

    /**
     * Shortcut to stop(Action::USER, $label, $success, $responseCode, $message, $data)
     */
    public function userStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        $timestamp = $this->getTimestamp();
        $this->stop(Action::USER, $label, $success, $responseCode, $message, $data, $timestamp);
    }

    /**
     * Shortcut to stop(Action::API, $label, $success, $responseCode, $message, $data)
     */
    public function apiStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        $timestamp = $this->getTimestamp();
        $this->stop(Action::API, $label, $success, $responseCode, $message, $data, $timestamp);
    }

    /**
     * Shortcut to stop(Action::PROCESS, $label, $success, $responseCode, $message, $data)
     */
    public function processStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        $timestamp = $this->getTimestamp();
        $this->stop(Action::PROCESS, $label, $success, $responseCode, $message, $data, $timestamp);
    }

    /**
     * This method stops all actions that have been started so far but not stopped
     *
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $message The message to associate with this action, if any
     */
    public function stopAll($success = true, $responseCode = '', $message = '', $data = null)
    {
        $timestamp = $this->getTimestamp();

        foreach ($this->currentActions as $action => $value) {
            /**
             * @var string $label
             * @var ProfilerAction $profileAction
             */
            foreach ($value as $label => $profileAction) {
                $profileAction->close($timestamp, $success, $responseCode, $message, $data);
                $this->completedActions[] = $profileAction;
            }
        }

        $this->currentActions = array();
    }

    /**
     * Returns whether an action is currently being profiled
     *
     * @param string $action The action type
     * @param string $label The name of the action to profile
     *
     * @see Action
     *
     * @return boolean
     */
    public function isStarted($action, $label)
    {
        return isset($this->currentActions[$action][$label]);
    }

    /**
     * Returns the list of actions that have been profiled
     * @return ProfilerAction[]
     */
    public function listCompletedActions()
    {
        return $this->completedActions;
    }

    private function getTimestamp()
    {
        // TODO Make sure time is synchronized with a reliable source and adjust accordingly
        return microtime(true);
    }

    public function loadConfigFile($configFile)
    {
        if (!file_exists($configFile)) {
            throw new \Exception('Invalid configFile provided: ' . $configFile);
        }
        $this->config = json_decode(file_get_contents($configFile), true);
    }
}