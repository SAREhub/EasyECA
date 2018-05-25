<?php

namespace SAREhub\EasyECA\Rule\Loader\ParsingStrategy;


interface HttpResponseParsingStrategy
{
    /**
     * @param array $data
     *
     * @tutorial
     * [
     *     'tag' => [
     *          1 => [
     *              ["condition" => "a is string"]
     *          ],
     *          10 => [
     *              ["condition" => "b is string"]
     *          ],
     *      ]
     * ]
     *
     * @return array
     */
    public function load(array $data): array;
}