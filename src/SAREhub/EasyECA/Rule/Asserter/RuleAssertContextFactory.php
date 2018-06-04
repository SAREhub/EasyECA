<?php

namespace SAREhub\EasyECA\Rule\Asserter;

interface RuleAssertContextFactory
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function create($data): array;
}

