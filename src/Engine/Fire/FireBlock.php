<?php

namespace VulcanPhp\SweetView\Engine\Fire;

use VulcanPhp\SweetView\Engine\Html\Html;

class FireBlock
{
    protected Html $html;
    protected array $params;
    
    public function __construct(Html $html, array $params = [])
    {
        $this->html = $html;
        $this->params = $params;
    }

    public static function render(...$args): void
    {
        $block = new self(...$args);
        $block->responseInJson();
    }

    public function responseInJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            $this->getBlockData(),
            JSON_UNESCAPED_UNICODE
        );
        exit;
    }


    public function getBlockData(): array
    {
        return [
            'content'   => $this->getBlockContent(),
            'blocks'    => $this->getBlocks(),
            'title'     => $this->getDocumentTitle(),
        ];
    }

    protected function getBlockContent(): string
    {
        return $this->html->getContent(
            $this->html->templateLocation(),
            $this->params
        );
    }

    protected function getDocumentTitle(): ?string
    {
        return $this->html->meta('title', null);
    }

    protected function getBlocks(): array
    {
        return $this->html->getBlocks();
    }
}
