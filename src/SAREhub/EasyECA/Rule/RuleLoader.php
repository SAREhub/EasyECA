<?php
namespace SAREhub\EasyECA\Rule;


interface RuleLoader
{
    public function load(): array;
}