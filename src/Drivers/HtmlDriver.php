<?php

namespace PhpScript\SweetView\Drivers;

use PhpScript\SweetView\Engine\Html\Html;
use PhpScript\SweetView\Interfaces\IViewDriver;

class HtmlDriver implements IViewDriver
{
    protected const EXTENSION = '.php', BASE_DIR = 'resources/views';
    protected Html $engine;

    public function __construct()
    {
        $this->engine = new Html;
        $this->engine->resourceDir(self::BASE_DIR)->extension(self::EXTENSION);
    }

    public function dispatchView(string $template, array $paramiters = []): string
    {
        return $this->getEngine()->template($template)->render($paramiters);
    }

    public function getEngine(): Html
    {
        return $this->engine;
    }
}
