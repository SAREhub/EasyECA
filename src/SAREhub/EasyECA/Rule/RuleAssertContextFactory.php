<?php


namespace SAREhub\EasyECA\Rule;


interface RuleAssertContextFactory
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function create($data): array;
}