<?php
namespace App\Services;

class Validator
{
    protected $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            foreach (explode('|', $rule) as $condition) {
                if ($condition === 'required' && empty($value)) {
                    $this->errors[$field][] = "{$field} is required.";
                }

                if ($condition === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$field} must be a valid email.";
                }

                if (strpos($condition, 'min:') === 0) {
                    $min = (int) str_replace('min:', '', $condition);
                    if (strlen($value) < $min) {
                        $this->errors[$field][] = "{$field} must be at least {$min} characters.";
                    }
                }
            }
        }
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }
}