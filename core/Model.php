<?php

namespace Framework;

class Model
{
    protected array $fillable = [];
    public array $attributes = [];

    public function loadData()
    {
        $data = request()->getData();
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            } else {
                $this->attributes[$field] = '';
            }
        }
        
    }
}