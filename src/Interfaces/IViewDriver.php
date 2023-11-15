<?php

namespace PhpScript\SweetView\Interfaces;

interface IViewDriver
{
    public function dispatchView(string $template, array $paramiters = []): string;
}
