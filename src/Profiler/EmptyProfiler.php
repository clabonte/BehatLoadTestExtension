<?php
namespace BehatProfiling\Profiler;

/**
 * Class EmptyProfiler
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
class EmptyProfiler implements ProfilerInterface
{

    /** @inheritdoc */
    public function start($action, $label)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function userStart($label)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function apiStart($label)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function processStart($label)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function stop($action, $label, $success = true, $responseCode = '', $message = '', $data = null, $timestamp = null)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function userStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function apiStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function processStop($label, $success = true, $responseCode = '', $message = '', $data = null)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function stopAll($success = true, $responseCode = '', $message = '', $data = null)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function isStarted($action, $label)
    {
        // Do nothing
    }

    /** @inheritdoc */
    public function listCompletedActions()
    {
        // Do nothing
    }
}