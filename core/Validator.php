<?php

namespace Framework;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $rule) {
            $fieldRules = explode('|', $rule);
            foreach ($fieldRules as $fieldRule) {
                $ruleName = $fieldRule;
                $parameter = null;
                if (str_contains($fieldRule, ':')) {
                    [$ruleName, $parameter] = explode(':', $fieldRule);
                }
                $this->applyRule($data, $field, $ruleName, $parameter);
            }
        }
        return empty($this->errors);
    }

    private function applyRule(array $data, string $field, string $ruleName, ?string $parameter = null): void
    {
        $value = $data[$field] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, "$field is required.");
                }
                break;
            case 'min':
                if (strlen((string)$value) < (int)$parameter) {
                    $this->addError($field, "$field must be at least $parameter characters.");
                }
                break;
            case 'max':
                if (strlen((string)$value) > (int)$parameter) {
                    $this->addError($field, "$field must be no more than $parameter characters.");
                }
                break;
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "$field must be a valid email address.");
                }
                break;
            case 'confirmation':
                $confirmationField = $field . 'Confirmation';
                if ($value !== ($data[$confirmationField] ?? null)) {
                    $this->addError($field, "$field must match the confirmation field.");
                }
                break;
            default:
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function error(string $field): ?array
    {
        return $this->errors[$field] ?? null;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}