<?php
namespace Stack;

class ContentFilter
{
    private $filters = array();

    public function __construct()
    {
        add_action('template_redirect', array($this, 'start'));
    }

    public function start()
    {
        $filters = apply_filters('stack_content_filters', array('Stack\\CDNOffloader'));
        foreach ($filters as $filter) {
            $f = new $filter();
            if ($f->enabled()) {
                array_push($this->filters, $f);
            }
        }
        if (empty($this->filters)) {
            return;
        }

        ob_start(array($this, 'filter'));
    }

    public function filter($content, $phase)
    {
        if ($phase & PHP_OUTPUT_HANDLER_FINAL || $phase & PHP_OUTPUT_HANDLER_END) {
            foreach ($this->filters as $filter) {
                $content = $filter->filter($content);
            }
            return $content;
        }

        return $content;
    }
}
