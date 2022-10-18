<?php

namespace Stack;

interface ContentFilterInterface
{
    public function enabled(): bool;
    public function filter(string $content): string;
}
