<?php

namespace PhpScript\SweetView\Interfaces;

interface IView
{
    public function render(...$args): string;

    public function getDriver(): IViewDriver;
}
