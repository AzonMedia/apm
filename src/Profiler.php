<?php
declare(strict_types=1);

namespace Azonmedia\Apm;

class Profiler implements ProfilerInterface
{

    protected $profile_data = ProfilerInterface::PROFILE_STRUCTURE;

    protected $backends = [];

    public function __construct(BackendInterface $Backend)
    {
        $this->add_backend($Backend);
    }
    
    public function __destruct()
    {
        $this->store();
    }
    
    public function add_backend(BackendInterface $Backend) : void
    {
        $this->backends[] = $Backend;
    }
    
    public function increment_value(string $key, float $amount) : void
    {
    
    }

    public function reset_data()
    {
        $this->profile_data = ProfilerInterface::PROFILE_STRUCTURE;
    }
    
    protected function store()
    {
        foreach ($this->backends as $Backend) {
            $Backend->store_data($this->profile_data);
        }
    }
    
    

}