<?php
namespace BehatProfiling\Profiler;

/**
 * Class ProfilerAction
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
class ProfilerAction
{
    /**
     * @var string The action type
     * @see Action
     */
    private $action;

    /** @var string The name of the action */
    private $label;

    /** @var float The timestamp (in microsecond) when the action was started */
    private $startTime = null;

    /** @var float The timestamp (in microsecond) when the action was stopped */
    private $stopTime = null;

    /** @var boolean Whether the action completed successfully */
    private $success;

    /** @var string The response code associated with the action */
    private $responseCode;

    /** @var string The message associated with the action */
    private $message;

    /** @var string|array Data associated with the action */
    private $data;

    /**
     * ProfilerAction constructor.
     * @param string $action
     * @param string $label
     * @param float $startTime
     */
    public function __construct($action, $label, $startTime)
    {
        $this->action = $action;
        $this->label = $label;
        $this->startTime = $startTime;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return float
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param float $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return float
     */
    public function getStopTime()
    {
        return $this->stopTime;
    }

    /**
     * @param float $stopTime
     */
    public function setStopTime($stopTime)
    {
        $this->stopTime = $stopTime;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param string $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function close($timestamp, $success, $responseCode, $message, $data)
    {
        $this->stopTime = $timestamp;
        $this->success = $success;
        $this->responseCode = $responseCode;
        $this->message = $message;
        $this->data = $data;
    }


}