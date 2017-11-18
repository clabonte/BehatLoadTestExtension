<?php
namespace BehatProfiling\Profiler;

/**
 * Class ProfilerFactory
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
class ProfilerFactory
{
    /** @var ProfilerInterface */
    static protected $profiler = null;

    public static function getProfiler()
    {
        if (self::$profiler == null) {
            // Profiler has not been enabled yet
            self::$profiler = new EmptyProfiler();
        }
        return self::$profiler;
    }

    public static function setProfiler(ProfilerInterface $profiler)
    {
        self::$profiler = $profiler;
    }
}