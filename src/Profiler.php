<?php
declare(strict_types=1);

namespace Azonmedia\Apm;

class Profiler implements ProfilerInterface
{

    protected $profile_data = [
	'execution_start_microtime'	=> 0,
	'execution_end_microtime'	=> 0,
	'cnt_used_connections'		=> 0,
	'time_used_connections'		=> 0,//for all connections
	'cnt_dql_statements'	=> 0,
	
    ];

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

    
    
    protected function store()
    {
	foreach ($this->backends as $Backend) {
	    $Backend->store_data($this->profile_data);
	}
    }
    
    

}