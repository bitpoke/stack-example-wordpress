<?php

Stack\Config::loadDefaults();

new Stack\URLFixer();
new Stack\MediaStorage();
new Stack\QuerySplit();
new Stack\NginxHelperActivator();
new Stack\MetricsCollector();
new Stack\ContentFilter();
