<?php

namespace Studiow\Laravel\Table\Column;

class DateColumn extends Column
{
    protected $format = 'Y-m-d';

    public function setFormat(string $format)
    {
        $this->format = $format;

        return $this;
    }

    public function resolve($row)
    {
        $value = parent::resolve($row);
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format($this->format);
        }

        return  (string) $value;
    }
}
