<?php
namespace BehatProfiling\Profiler;

use MyCLabs\Enum\Enum;

/**
 * Class Action
 * @package BehatLoadTest\Profiler
 * @author Christian Labonté
 */
class Action extends Enum
{
    const SUITE = 'suite';
    const FEATURE = 'feature';
    const SCENARIO = 'scenario';
    const STEP = 'step';
    const USER = 'user';
    const API = 'api';
    const PROCESS = 'process';
}