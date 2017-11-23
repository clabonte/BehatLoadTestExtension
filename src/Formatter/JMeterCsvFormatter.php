<?php
namespace BehatProfiling\Formatter;

use BehatProfiling\Profiler\ProfilerAction;

/**
 * Class JMeterCsvFormatter
 * @package BehatLoadTest\Formatter
 * @author Christian Labonté
 */
class JMeterCsvFormatter implements FormatterInterface
{
    /** @var resource */
    private $fp;
    /** @var string  */
    private $delimiter;
    /** @var string  */
    private $enclosure;
    /** @var string  */
    private $escapeChar;

    public function __construct($filename, $delimiter = ",", $enclosure = '"', $escapeChar = '\\')
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escapeChar = $escapeChar;
        if (file_exists($filename)) {
            $this->fp = fopen($filename, 'a');
        } else {
            $this->fp = fopen($filename, 'w');
            $this->printHeader();
        }
    }

    /**
     * Formats a list of ProfilerAction objects
     * @param array $profilerActions
     */
    public function formatProfilerActions(array $profilerActions, $group = '')
    {
        $fields = array();

        /** @var ProfilerAction $profilerAction */
        foreach ($profilerActions as $profilerAction) {
            $startTime = $profilerAction->getStartTime();
            $stopTime = $profilerAction->getStopTime();
            $duration = $stopTime - $startTime;
            list($sec, $usec) = explode('.', $startTime);

            $fields[0] = date('Y-m-d H:i:s', $sec);
            $fields[1] = (int) ($duration * 1000);
            $fields[2] = $profilerAction->getAction() .'.'.$profilerAction->getLabel();
            $fields[3] = $profilerAction->getResponseCode();
            $fields[4] = $group;
            $fields[5] = 'text';
            $fields[6] = $profilerAction->isSuccess() ? 'true' : 'false';
            $fields[7] = $profilerAction->getMessage();

            fputcsv($this->fp, $fields, $this->delimiter, $this->enclosure, $this->escapeChar);
        }
    }

    public function close()
    {
        fclose($this->fp);
    }

    private function printHeader()
    {
        $header = array('timeStamp','elapsed','label','responseCode','threadName','dataType','success','failureMessage');
        fputcsv($this->fp, $header, $this->delimiter, $this->enclosure, $this->escapeChar);
    }

}