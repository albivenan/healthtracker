<?php

namespace HealthTracker\Controllers;

class Controller {
    protected function checkAuth() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login or throw an exception
            header('Location: /login');
            exit;
        }

        return $_SESSION['user_id'];
    }

    protected function view($viewPath, $data = []) {
        // Extract data to local variables
        extract($data);

        // Construct full path to view
        $fullPath = __DIR__ . '/../../views/' . $viewPath . '.php';

        // Check if view exists
        if (!file_exists($fullPath)) {
            throw new \Exception("View not found: " . $viewPath);
        }

        // Include the view
        require $fullPath;
    }

    protected function jsonResponse($data, $statusCode = 200) {
        // Set JSON header
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        // Output JSON
        echo json_encode($data);
        exit;
    }
}
