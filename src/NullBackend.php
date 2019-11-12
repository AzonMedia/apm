<?php
declare(strict_types=1);


namespace Azonmedia\Apm;

use Azonmedia\Apm\Interfaces\BackendInterface;

class NullBackend implements BackendInterface
{
    public function store_data(array $data) : void
    {
        //does nothing
    }

    public function store_data_end_time(array $data) : void
    {
        //does nothing
    }
}