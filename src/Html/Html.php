<?php

namespace PhpScript\SweetView\Html;

use Exception;

class Html extends HtmlViewer
{
    use HtmlMeta;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
    }

    public static function load(...$args): Html
    {
        return new Html(...$args);
    }

    public function clean(): self
    {
        if (isset(ob_get_status()['buffer_size']) && ob_get_status()['buffer_size'] > 0) {

            if (function_exists('do_action')) {
                do_action('html_clean_output');
            }

            ob_end_clean();
            flush();
        }

        return $this;
    }

    public function render(array $params = []): string
    {
        $content = $this->getContent($this->viewLocation($this->getTemplate()), $params);

        if ($this->getLayout() !== null) {
            $content = str_ireplace(['{{content}}', '{{ content }}'], $content, $this->getContent($this->viewLocation($this->getLayout())));
        }

        if (function_exists('apply_filter')) {
            $content = apply_filter('html_render', $content);
        }

        return $this->isMinified() ? preg_replace(['/^ {2,}/m', '/^\t{2,}/m', '~[\r\n]+~'], '',  $content) : $content;
    }

    public function include(...$args)
    {
        return $this->selfLoad('include', ...$args);
    }

    public function require(...$args)
    {
        return $this->selfLoad('require', ...$args);
    }

    public function includeOnce(...$args)
    {
        return $this->selfLoad('include_once', ...$args);
    }

    public function requireOnce(...$args)
    {
        return $this->selfLoad('require_once', ...$args);
    }

    public function component(string $component, array $params = []): self
    {
        extract($params);
        unset($params);

        include $this->viewLocation($component);
        return $this;
    }

    protected function selfLoad(string $type, string $file, array $params = []): self
    {
        $params = array_merge($this->params, $params);
        extract($params);
        unset($params);

        switch ($type) {
            case 'include_once':
                include_once $this->viewLocation($file);
                break;
            case 'require_once':
                require_once $this->viewLocation($file);
                break;
            case 'require':
                require $this->viewLocation($file);
                break;
            default:
                include $this->viewLocation($file);
        }

        return $this;
    }

    protected function viewLocation(string $file): string
    {
        return $this->directoryParse(sprintf('%s/%s', stripos($file, sweet_view_root_dir()) === false ? ($this->resource_dir ?? '') : sweet_view_root_dir(), str_ireplace(sweet_view_root_dir(), '', str_replace(['.'], '/', $file)))) . $this->getExtension();
    }

    protected function directoryParse(string $path): string
    {
        // remove view file extension
        $path = str_ireplace([$this->getExtension(), '.html', '.xml', '.yml', '.xhtml', '.js', '.vue', '.blade', '.view', '.blade.php', '.view.php'], '', $path);

        // parse path with directory separator
        return rtrim(str_replace(['///', '//', '/', (DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR)], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

    protected function getContent(string $path, array $params = []): string
    {
        if (!file_exists($path))
            throw new Exception('view file: ' . $path . ' does not exists');

        $params  = array_merge($this->params, $params);

        extract($params);
        unset($params);
        ob_start();

        if (function_exists('do_action')) {
            do_action('before_html_output_buffer', $path);
        }

        include $path;

        if (function_exists('do_action')) {
            do_action('after_html_output_buffer', $path);
        }

        return ob_get_clean();
    }
}
