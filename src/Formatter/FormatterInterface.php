<?php
namespace BehatProfiling\Formatter;

/**
 * Interface FormatterInterface
 * @package BehatLoadTest\Formatter
 * @author Christian Labonté
 */
interface FormatterInterface
{
    /**
     * Formats a list of ProfilerAction objects
     * @param array $profilerActions
     */
    public function formatProfilerActions(array $profilerActions, $group = '');
}