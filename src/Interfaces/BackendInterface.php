<?php
declare(strict_types=1);

namespace Azonmedia\Apm\Interfaces;

interface BackendInterface
{
    public function store_data(array $data) : void ;
}