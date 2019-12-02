<?php
declare(strict_types=1);

namespace Azonmedia\Apm;

use Azonmedia\Apm\Interfaces\ProfilerInterface;
use Azonmedia\Apm\Interfaces\BackendInterface;
use Azonmedia\Di\Interfaces\CoroutineDependencyInterface;


class Profiler implements ProfilerInterface, CoroutineDependencyInterface
{

    protected array $profile_data = ProfilerInterface::PROFILE_STRUCTURE;

    protected array $backends = [];

    protected bool $data_stored_flag = FALSE;

    public function __construct(BackendInterface $Backend, int $worker_id = -1)
    {
        $this->add_backend($Backend);

        //$this->profile_data['worker_id'] = Kernel::get_worker_id();
        $this->profile_data['worker_id'] = $worker_id;
        $this->profile_data['coroutine_id'] = \Swoole\Coroutine::getCid();
        $this->profile_data['execution_start_microtime'] = microtime(TRUE) * 1_000_000;
    }
    
    public function __destruct()
    {
        $this->profile_data['execution_end_microtime'] = microtime(TRUE);
        $this->profile_data['cnt_total_current_coroutines'] = count(\Swoole\Coroutine::listCoroutines());

        $this->store_data_end_time();
    }

    public function __toString() : string
    {
        return print_r($this->profile_data, TRUE);
    }
    
    public function add_backend(BackendInterface $Backend) : void
    {
        $this->backends[] = $Backend;
    }
    
    public function increment_value(string $key, float $amount) : void
    {
        if (!array_key_exists($key, $this->profile_data)) {
            throw new \InvalidArgumentException(sprintf('There is no key named "%s" in the $profile_data.', $key));
        }
        $this->profile_data[$key] += $amount;
    }

    public function reset_data() : void
    {
        $this->profile_data = ProfilerInterface::PROFILE_STRUCTURE;
    }
    
    public function store_data() : void
    {
        if ($this->is_data_stored()) {
            return;
        }
        foreach ($this->backends as $Backend) {
            $Backend->store_data($this->profile_data);
        }
        $this->data_stored_flag = TRUE;
    }

    public function store_data_end_time() : void
    {
        foreach ($this->backends as $Backend) {
            $Backend->store_data_end_time($this->profile_data);
        }
    }

    public function is_data_stored() : bool
    {
        return $this->data_stored_flag;
    }

    public function get_data() : array
    {
        return $this->profile_data;
    }
    
    

}