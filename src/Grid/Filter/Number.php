<?php

namespace Nicelizhi\Admin\Grid\Filter;

use Illuminate\Support\Arr;

class Number extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected $query = 'whereIn';

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function condition($inputs)
    {
        $value = Arr::get($inputs, $this->column);

        var_dump($value);exit;

        if (is_null($value)) {
            return;
        }

        $this->value = (array) $value;

        return $this->buildCondition($this->column, $this->value);
    }
}
