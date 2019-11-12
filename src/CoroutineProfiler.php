<?php


namespace Azonmedia\Apm;


use Azonmedia\Apm\Interfaces\BackendInterface;
use Azonmedia\Apm\Interfaces\ProfilerInterface;
use Swoole\Coroutine;

class CoroutineProfiler implements ProfilerInterface
{

    protected BackendInterface $Backend;

    protected int $worker_id;

    /**
     * CoroutineProvider constructor.
     * In coroutine context the $worker_id is a mandatory argument unlike in Profiler.
     * @param BackendInterface $Backend
     * @param int $worker_id
     */
    public function __construct(BackendInterface $Backend, int $worker_id)
    {
        $this->Backend = $Backend;
        $this->worker_id = $worker_id;
    }

    private function initialize_profiler()
    {
        if (Coroutine::getCid() === -1) {
            throw new \RuntimeException(sprintf('The %s must be used/created in Coroutine context. When outside Coroutine context please use %s.', __CLASS__, Profiler::class));
        }
        $Context = Coroutine::getContext();
        if (empty($Context->{ProfilerInterface::class})) {
            $Context->{ProfilerInterface::class} = new Profiler($this->Backend, $this->worker_id);
        }
    }

    public function add_backend(BackendInterface $Backend) : void
    {
        $this->initialize_profiler();
        Coroutine::getContext()->{ProfilerInterface::class}->add_backend($Backend);
    }

    public function increment_value(string $key, float $amount) : void
    {
        $this->initialize_profiler();
        Coroutine::getContext()->{ProfilerInterface::class}->increment_value($key, $amount);
    }

    public function reset_data() : void
    {
        $this->initialize_profiler();
        Coroutine::getContext()->{ProfilerInterface::class}->reset_data();
    }

    public function store_data() : void
    {
        $this->initialize_profiler();
        Coroutine::getContext()->{ProfilerInterface::class}->store_data();
    }

    public function store_data_end_time() : void
    {
        $this->initialize_profiler();
        Coroutine::getContext()->{ProfilerInterface::class}->store_data_end_time();
    }

    public function is_data_stored() : bool
    {
        $this->initialize_profiler();
        return Coroutine::getContext()->{ProfilerInterface::class}->is_data_stored();
    }

    public function get_data() : array
    {
        $this->initialize_profiler();
        return Coroutine::getContext()->{ProfilerInterface::class}->get_data();
    }
}