<?php

class Benchmark
{
    protected $targets;
    protected $results;
    protected $iterations = 1000;
    protected $warmUpIterations = 100;

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param string $name
     * @param callable $target
     * @param array $parameters
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addTarget($name, $target, array $parameters = array())
    {
        if (!is_callable($target)) {
            throw new \InvalidArgumentException('Argument $target has to be callable');
        }
        
        $this->targets[$name] = array(
            'callable' => $target, 'parameters' => $parameters
        );
        
        return $this;
    }

    /**
     * @param int $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setIterations($value)
    {
        $value = (int) $value;

        if ($value < 1) {
            throw new \InvalidArgumentException('Argument $value has to be integer and be greater than 0');
        }

        $this->iterations = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setWarmUpIterations($value)
    {
        $value = (int) $value;

        if ($value < 1) {
            throw new \InvalidArgumentException('Argument $value has to be integer and be greater than 0');
        }

        $this->warmUpIterations = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function warmUp()
    {
        foreach ($this->targets as $name => $target) {
            for ($i = 0; $i < $this->warmUpIterations; $i++) {
                call_user_func_array($target['callable'], $target['parameters']);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        foreach ($this->targets as $name => $target) {
            $this->results[$name] = $this->doBench($target);
        }
        
        asort($this->results);
        
        foreach ($this->results as $name => $time) {
            if (!isset($minTime)) {
                $minTime = $time;
                $percent = 100;
            } else {
                $percent = (int) round($time / $minTime * 100);
            }

            $this->results[$name] = array(
                'time' => $time,
                'percent' => $percent,
            );
        }
        
        return $this;
    }

    /**
     * @param array $target
     * @return float
     */
    protected function doBench($target)
    {
        $startTime = microtime(true);
        
        for ($i=0; $i<$this->iterations; $i++) {
            call_user_func_array($target['callable'], $target['parameters']);
        }
        
        return microtime(true) - $startTime;
    }
}
