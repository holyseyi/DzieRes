<?php
/**
 * Base Controller
 * Restaurant Management System
 */

namespace Controllers;

abstract class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        \view($view, $data);
    }

    protected function partial(string $partial, array $data = []): void
    {
        \partial($partial, $data);
    }

    protected function json($data, int $statusCode = 200): void
    {
        \jsonResponse(['data' => $data], $statusCode);
    }

    protected function success($data = null, string $message = 'Success'): void
    {
        \jsonSuccess($data, $message);
    }

    protected function error(string $message = 'Error', int $statusCode = 400, $errors = null): void
    {
        \jsonError($message, $statusCode, $errors);
    }

    protected function redirect(string $url): void
    {
        \redirect($url);
    }

    protected function back(): void
    {
        \back();
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $ruleList = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);
            $value = $data[$field] ?? '';
            
            foreach ($ruleList as $rule) {
                $params = [];
                
                if (strpos($rule, ':') !== false) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }
                
                switch ($rule) {
                    case 'required':
                        if (empty($value) && $value !== '0') {
                            $errors[$field][] = "The {$field} field is required";
                        }
                        break;
                    case 'email':
                        if (!empty($value) && !\validateEmail($value)) {
                            $errors[$field][] = "The {$field} must be a valid email";
                        }
                        break;
                    case 'phone':
                        if (!empty($value) && !\validatePhone($value)) {
                            $errors[$field][] = "The {$field} must be a valid phone number";
                        }
                        break;
                    case 'min':
                        $min = (int)($params[0] ?? 0);
                        if (strlen($value) < $min) {
                            $errors[$field][] = "The {$field} must be at least {$min} characters";
                        }
                        break;
                    case 'max':
                        $max = (int)($params[0] ?? 255);
                        if (strlen($value) > $max) {
                            $errors[$field][] = "The {$field} must not exceed {$max} characters";
                        }
                        break;
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field][] = "The {$field} must be a number";
                        }
                        break;
                    case 'integer':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                            $errors[$field][] = "The {$field} must be an integer";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }

    protected function renderWithLayout(string $view, array $data = [], string $layout = 'main'): void
    {
        $data['contentView'] = $view;
        $this->view("layouts/{$layout}", $data);
    }

    protected function renderAdmin(string $view, array $data = []): void
    {
        $data['contentView'] = $view;
        $this->view('admin/layouts/master', $data);
    }
}