<?php

namespace Framework;

class ErrorHandler
{
    protected bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;

        // Устанавливаем обработчики ошибок и исключений
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    // Обработчик ошибок
    public function handleError(int $level, string $message, string $file, int $line): bool
    {
        // Преобразуем ошибку в исключение для удобства обработки
        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    // Обработчик исключений
    public function handleException(\Throwable $exception): void
    {
        $this->logError($exception); // Логируем ошибку
        $this->displayError($exception); // Отображаем ошибку, если режим отладки включен
    }

    // Обработчик фатальных ошибок
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $exception = new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
    }

    // Метод для логирования ошибок в файл
    protected function logError(\Throwable $exception): void
    {
        $logMessage = sprintf(
            "========== ERROR LOG ==========\n" .
            "Date       : %s\n" .
            "Error Type : %s\n" .
            "Message    : %s\n" .
            "File       : %s\n" .
            "Line       : %d\n" .
            "Stack Trace:\n%s\n" .
            "===============================\n\n",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        file_put_contents(APP_PATH . '/tmp/errors.log', $logMessage, FILE_APPEND);
    }

    // Метод для отображения ошибки в режиме отладки
    protected function displayError(\Throwable $exception): void
    {
        if ($this->debug) {
            echo "<h1>Application Error</h1>";
            echo "<p><strong>Type:</strong> " . get_class($exception) . "</p>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . $exception->getFile() . " on line " . $exception->getLine() . "</p>";
            echo "<h2>Stack trace</h2>";
            echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        } else {
            // В продакшн-режиме можно вывести общее сообщение
            echo "<h1>Something went wrong. Please try again later.</h1>";
        }
    }
}