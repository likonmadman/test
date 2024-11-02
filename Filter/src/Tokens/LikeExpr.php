<?php

namespace CP\Filter\Tokens;

class LikeExpr implements ASTNode
{
    protected $field;
    protected $pattern;

    public function __construct(FldVal $field, StrVal $pattern)
    {
        $this->field = $field;
        $this->pattern = $pattern;
    }

    public function apply(array $data): bool
    {
        $fieldName = $this->field->getValue();
        $pattern = str_replace('%', '.*', preg_quote($this->pattern->getValue(), '/'));
        return isset($data[$fieldName]) && preg_match("/^$pattern$/i", $data[$fieldName]);
    }
}