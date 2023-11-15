<?php

namespace PhpScript\SweetView\Html;

abstract class HtmlViewer
{
    protected $resource_dir, $extension, $minified, $params = [], $blocks = [];
    protected ?string $template, $layout;

    public function __construct($template = null, $layout = null)
    {
        $this->template = $template;
        $this->layout = $layout;
    }

    abstract public function render(array $params = []): string;
    abstract protected function getContent(string $path, array $params = []): string;

    public function template(string $template): self
    {
        if (stripos($template, sweet_view_root_dir()) !== false) {
            $location = $this->directoryParse(str_replace('.', '/', $template));
            $this->resource_dir = substr($location, 0, strrpos($location, DIRECTORY_SEPARATOR) + 1);
            $this->template = substr($location, strrpos($location, DIRECTORY_SEPARATOR) + 1);
        } else {
            $this->template = $template;
        }

        return $this;
    }

    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function getBlock(string $key, $default = '')
    {
        return $this->blocks[$key] ?? $default;
    }

    public function hasBlock(string $key): bool
    {
        return isset($this->blocks[$key]);
    }

    public function block(string $key, $value): self
    {
        $this->blocks[$key] = $value;
        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout ?? null;
    }

    public function getTemplate(): ?string
    {
        return $this->template ?? null;
    }

    public function resourceDir(string $path): self
    {
        $this->resource_dir = $path;
        return $this;
    }

    public function with(array $params = []): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function minified($minified = true): self
    {
        $this->minified = $minified;
        return $this;
    }

    public function isMinified(): bool
    {
        return boolval($this->minified ?? false);
    }

    public function extension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension ?? '.php';
    }
}
