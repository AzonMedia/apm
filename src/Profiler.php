<?php
declare(strict_types=1);

namespace Azonmedia\Apm;

use Azonmedia\Apm\Interfaces\ProfilerInterface;
use Azonmedia\Apm\Interfaces\BackendInterface;

class Profiler implements ProfilerInterface
{

    protected $profile_data = ProfilerInterface::PROFILE_STRUCTURE;

    protected $backends = [];

    protected $data_stored_flag = FALSE;

    public function __construct(BackendInterface $Backend)
    {
        $this->add_backend($Backend);
    }
    
    public function __destruct()
    {
        $this->store_data();
    }
    
    public function add_backend(BackendInterface $Backend) : void
    {
        $this->backends[] = $Backend;
    }
    
    public function increment_value(string $key, float $amount) : void
    {
    
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

    public function is_data_stored() : bool
    {
        return $this->data_stored_flag;
    }

    public function get_data() : array
    {
        return $this->profile_data;
    }
    
    

}