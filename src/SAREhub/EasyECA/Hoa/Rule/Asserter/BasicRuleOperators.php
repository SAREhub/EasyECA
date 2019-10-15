<?php

namespace SAREhub\EasyECA\Hoa\Rule\Asserter;

use Hoa\Ruler\Visitor\Asserter;

class BasicRuleOperators
{

    /**
     * @param string $pattern
     * @param array $subject
     * @return mixed
     */
    public function getValueOfFirstMatchedKeyInArray(string $pattern, array $subject)
    {
        foreach ($subject as $key => $value) {
            if (preg_match($pattern, $key) === 1) {
                return $value;
            }
        }
        return null;
    }

    public function registerInAsserter(Asserter $asserter)
    {
        $asserter->setOperator("getvalueoffirstmatchedkeyinarray", [$this, "getValueOfFirstMatchedKeyInArray"]);
        $asserter->setOperator("strpos", "strpos");
        $asserter->setOperator("explode", "explode");
        $asserter->setOperator("strtolower", "strtolower");
        $asserter->setOperator("===", function ($a, $b) {
            return $a === $b;
        });
        $asserter->setOperator("!==", function ($a, $b) {
            return $a !== $b;
        });
    }
}
