<?php
/**
 * Simple MVC Router
 * Restaurant Management System
 */

class Router
{
    private $routes = [];
    private $middleware = [];
    private $prefix = '';
    private $notFoundHandler = null;

    public function get(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    public function any(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET|POST|PUT|DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $methods, string $path, $handler, array $middleware): void
    {
        $path = $this->prefix . $path;
        $methods = explode('|', $methods);
        
        foreach ($methods as $method) {
            $this->routes[] = [
                'method' => strtoupper(trim($method)),
                'path' => $path,
                'handler' => $handler,
                'middleware' => array_merge($this->middleware, $middleware),
                'pattern' => $this->pathToRegex($path),
            ];
        }
    }

    public function group(string $prefix, callable $callback, array $middleware = []): void
    {
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->middleware;
        
        $this->prefix = $previousPrefix . $prefix;
        $this->middleware = array_merge($previousMiddleware, $middleware);
        
        $callback($this);
        
        $this->prefix = $previousPrefix;
        $this->middleware = $previousMiddleware;
    }

    public function setNotFound(string $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }
        
        // Find matching route
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                
                // Run middleware
                foreach ($route['middleware'] as $middleware) {
                    $this->runMiddleware($middleware);
                }
                
                // Execute handler
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }
        
        // 404 handler
        if ($this->notFoundHandler) {
            $this->executeHandler($this->notFoundHandler, []);
        } else {
            http_response_code(404);
            view('errors/404', ['message' => 'Page not found']);
        }
    }

    private function pathToRegex(string $path): string
    {
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        return '/^' . $pattern . '$/';
    }

    private function runMiddleware(string $middleware): void
    {
        if (is_callable($middleware)) {
            $middleware();
        } elseif (function_exists($middleware)) {
            $middleware();
        } elseif (method_exists($this, $middleware)) {
            $this->$middleware();
        }
    }

    private function resolveControllerClass(string $controller): string
    {
        // Already fully qualified with a known namespace
        if (strncmp($controller, 'Api\\', 4) === 0
            || strncmp($controller, 'Controllers\\', 12) === 0) {
            return $controller;
        }

        // Namespaced (e.g. Admin\DashboardController) => under Controllers\
        if (strpos($controller, '\\') !== false) {
            return 'Controllers\\' . $controller;
        }

        // Bare name (e.g. HomeController) => Controllers\HomeController
        return 'Controllers\\' . $controller;
    }

    private function executeHandler($handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            // Format: Controller@method
            if (strpos($handler, '@') !== false) {
                [$controller, $method] = explode('@', $handler);

                $controller = $this->resolveControllerClass($controller);
                
                if (!class_exists($controller)) {
                    throw new \RuntimeException("Controller not found: {$controller}");
                }
                
                $instance = new $controller();
                if (!method_exists($instance, $method)) {
                    throw new \RuntimeException("Method not found: {$controller}@{$method}");
                }
                
                call_user_func_array([$instance, $method], $params);
            } else {
                // Assume it's a view name
                view($handler, $params);
            }
        }
    }
}