<?php
namespace BehatProfiling\Profiler;

/**
 * Interface ProfilerInterface
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
interface ProfilerInterface
{
    /**
     * This method is called to start the profiling of an action.
     *
     * @param string $action The action type
     * @param string $label The name of the action to profile
     *
     * @see Action
     */
    public function start($action, $label);

    /**
     * Starts the profiling of a user-defined action
     * Shortcut to start(Action::USER, $label)
     *
     * @param string $label The name of the action to profile
     *
     * @see Action
     */
    public function userStart($label);

    /**
     * Starts the profiling of an API action
     * Shortcut to start(Action::API, $label)
     *
     * @param string $label The name of the action to profile
     *
     * @see Action
     */
    public function apiStart($label);

    /**
     * Starts the profiling of an process action
     * Shortcut to start(Action::PROCESS, $label)
     *
     * @param string $label The name of the action to profile
     *
     * @see Action
     */
    public function processStart($label);

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
    public function stop($action, $label, $success = true, $responseCode = '', $message = '', $data = null, $timestamp = null);

    /**
     * Stops the profiling of a user-defined action.
     * Shortcut to stop(Action::USER, $label, $success, $responseCode, $message, $data)
     *
     * @param string $label The name of the action to profile
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $responseCode The response code to associate with this action, if any
     * @param string $message The message to associate with this action, if any
     * @param string|array $data data to associate with this action, if any
     *
     * @see Action
     */
    public function userStop($label, $success = true, $responseCode = '', $message = '', $data = null);

    /**
     * Stops the profiling of an API action.
     * Shortcut to stop(Action::API, $label, $success, $responseCode, $message, $data)
     *
     * @param string $label The name of the action to profile
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $responseCode The response code to associate with this action, if any
     * @param string $message The message to associate with this action, if any
     * @param string|array $data data to associate with this action, if any
     *
     * @see Action
     */
    public function apiStop($label, $success = true, $responseCode = '', $message = '', $data = null);

    /**
     * Stops the profiling of a process action.
     * Shortcut to stop(Action::PROCESS, $label, $success, $responseCode, $message, $data)
     *
     * @param string $label The name of the action to profile
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $responseCode The response code to associate with this action, if any
     * @param string $message The message to associate with this action, if any
     * @param string|array $data data to associate with this action, if any
     *
     * @see Action
     */
    public function processStop($label, $success = true, $responseCode = '', $message = '', $data = null);

    /**
     * This method stops all actions that have been started so far but not stopped
     *
     * @param boolean $success Whether or not the action has completed successfully
     * @param string $message The message to associate with this action, if any
     */
    public function stopAll($success = true, $responseCode = '', $message = '', $data = null);

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
    public function isStarted($action, $label);

    /**
     * Returns the list of actions that have been profiled
     * @return ProfilerAction[]
     */
    public function listCompletedActions();
}