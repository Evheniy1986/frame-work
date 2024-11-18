<?php

namespace Framework;

class Model
{
    protected array $fillable = [];
    protected string $table;
    public array $attributes = [];

    public function loadData()
    {
        $data = request()->all();
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            } else {
                $this->attributes[$field] = '';
            }
        }
        
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}