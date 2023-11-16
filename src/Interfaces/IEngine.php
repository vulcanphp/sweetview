<?php

namespace PhpScript\SweetView\Interfaces;

interface IEngine
{
    public function render(array $params = []): string;

    public function getContent(string $path, array $params = []): string;
}
