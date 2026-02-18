<?php

namespace Core;

abstract class ServiceProvider
{
    /**
     * Servisleri sisteme kaydetmek için kullanılır.
     */
    abstract public function register();

    /**
     * Servisleri başlatmak için kullanılır.
     */
    abstract public function boot();
}
