<?php

use HealthTracker\Controllers\AuthController;
use HealthTracker\Controllers\HealthDataController;
use HealthTracker\Controllers\ActivityController;
use HealthTracker\Controllers\FoodJournalController;
use HealthTracker\Controllers\WorkoutScheduleController;
use HealthTracker\Controllers\MedicalHistoryController;
use HealthTracker\Controllers\BMIController;
use HealthTracker\Controllers\ProfileController;

try {
    // Load bootstrap
    $config = require_once __DIR__ . '/../src/bootstrap.php';

    // Hapus /albi dari URL untuk routing internal
    $request_uri = $_SERVER['REQUEST_URI'];
    $base_path = '/albi';
    
    // Log request untuk debugging
    error_log("Request URI: " . $request_uri);
    
    if (strpos($request_uri, $base_path) === 0) {
        $request_uri = substr($request_uri, strlen($base_path));
    }
    if (empty($request_uri)) {
        $request_uri = '/';
    }

    // Log processed URI
    error_log("Processed URI: " . $request_uri);

    // Parse URL
    $uri = parse_url($request_uri, PHP_URL_PATH);

    // Basic routing
    $routes = [
        '/' => function() {
            require __DIR__ . '/../views/home.php';
        },
        '/login' => function() {
            $auth = new AuthController();
            return $auth->login();
        },
        '/register' => function() {
            $auth = new AuthController();
            return $auth->register();
        },
        '/logout' => function() {
            $auth = new AuthController();
            return $auth->logout();
        }
    ];

    // Protected routes that require authentication
    $protectedRoutes = [
        '/dashboard' => function() {
            require __DIR__ . '/../views/dashboard.php';
        },
        '/health-data' => function() {
            $controller = new HealthDataController();
            return $controller->store();
        },
        '/health-data/weekly' => function() {
            $controller = new HealthDataController();
            return $controller->getWeeklyData();
        },
        '/activity' => function() {
            $controller = new ActivityController();
            return $controller->store();
        },
        '/activity/daily' => function() {
            $controller = new ActivityController();
            return $controller->getDailyActivity();
        },
        '/activity/weekly' => function() {
            $controller = new ActivityController();
            return $controller->getWeeklyActivity();
        },
        // Food Journal routes
        '/food-journal' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new FoodJournalController();
                return $controller->store();
            }
            $controller = new FoodJournalController();
            return $controller->index();
        },
        '/food-journal/create' => function() {
            $controller = new FoodJournalController();
            return $controller->create();
        },
        '/food-journal/weekly' => function() {
            $controller = new FoodJournalController();
            return $controller->getWeeklyJournal();
        },
        // Workout routes
        '/workout-schedule' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new WorkoutScheduleController();
                return $controller->create();
            }
            $controller = new WorkoutScheduleController();
            return $controller->index();
        },
        '/workout-schedule/data' => function() {
            $controller = new WorkoutScheduleController();
            return $controller->getWorkouts();
        },
        // Medical History routes
        '/medical-history' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new MedicalHistoryController();
                return $controller->index();
            }
        },
        '/medical-history/create' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new MedicalHistoryController();
                return $controller->add();
            }
        },
        '/medical-history/update' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new MedicalHistoryController();
                return $controller->update();
            }
        },
        '/medical-history/delete' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new MedicalHistoryController();
                return $controller->delete();
            }
        },
        '/medical-history/history' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new MedicalHistoryController();
                return $controller->getHistory();
            }
        },
        '/medical-history/get' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new MedicalHistoryController();
                return $controller->get();
            }
        },
        '/medical-history/active-conditions' => function() {
            $controller = new MedicalHistoryController();
            return $controller->getActiveConditions();
        },
        '/medical-history/conditions' => function() {
            $controller = new MedicalHistoryController();
            return $controller->getConditions();
        },
        // BMI routes
        '/bmi' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new BMIController();
                return $controller->index();
            }
        },
        '/bmi/create' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new BMIController();
                return $controller->create();
            }
        },
        '/bmi/update' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new BMIController();
                return $controller->update();
            }
        },
        '/bmi/delete' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new BMIController();
                return $controller->delete();
            }
        },
        '/bmi/history' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new BMIController();
                return $controller->getHistory();
            }
        },
        '/bmi/latest' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new BMIController();
                return $controller->getLatest();
            }
        },
        // Profile routes
        '/profile' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new ProfileController();
                return $controller->update();
            }
            $controller = new ProfileController();
            return $controller->index();
        },
        '/profile/data' => function() {
            $controller = new ProfileController();
            return $controller->getProfile();
        },
        '/profile/upload-photo' => function() {
            $controller = new ProfileController();
            return $controller->uploadPhoto();
        }
    ];

    // Dynamic routes for food journal
    if (preg_match('#^/food-journal/edit/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new FoodJournalController();
            return $controller->edit($matches[1]);
        };
    }

    if (preg_match('#^/food-journal/update/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new FoodJournalController();
            return $controller->update($matches[1]);
        };
    }

    if (preg_match('#^/food-journal/delete/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new FoodJournalController();
            return $controller->delete($matches[1]);
        };
    }

    // Dynamic routes for workout
    if (preg_match('#^/workout-schedule/get/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new WorkoutScheduleController();
            return $controller->getWorkout($matches[1]);
        };
    }

    if (preg_match('#^/workout-schedule/update/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new WorkoutScheduleController();
            return $controller->update($matches[1]);
        };
    }

    if (preg_match('#^/workout-schedule/delete/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new WorkoutScheduleController();
            return $controller->delete($matches[1]);
        };
    }

    // Dynamic routes for medical history
    if (preg_match('#^/medical-history/update/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new MedicalHistoryController();
            return $controller->update($matches[1]);
        };
    }

    if (preg_match('#^/medical-history/delete/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new MedicalHistoryController();
            return $controller->delete($matches[1]);
        };
    }

    if (preg_match('#^/medical-history/recommendations/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new MedicalHistoryController();
            return $controller->getRecommendations($matches[1]);
        };
    }

    if (preg_match('#^/medical-history/trend/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new MedicalHistoryController();
            return $controller->getHealthTrend($matches[1]);
        };
    }

    // Dynamic routes for BMI
    if (preg_match('#^/bmi/update/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new BMIController();
            return $controller->update($matches[1]);
        };
    }

    if (preg_match('#^/bmi/delete/([^/]+)$#', $uri, $matches)) {
        $protectedRoutes[$uri] = function() use ($matches) {
            $controller = new BMIController();
            return $controller->delete($matches[1]);
        };
    }

    // Log current route
    error_log("Current route: " . $uri);

    // Check if route exists
    if (array_key_exists($uri, $routes)) {
        $routes[$uri]();
    } elseif (array_key_exists($uri, $protectedRoutes)) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base_path . '/login');
            exit;
        }
        $protectedRoutes[$uri]();
    } else {
        error_log("404 Not Found: " . $uri);
        http_response_code(404);
        require __DIR__ . '/../views/404.php';
    }   
} catch (Throwable $e) {
    // Log full error details
    error_log("Fatal error: " . $e->getMessage());
    error_log("File: " . $e->getFile());
    error_log("Line: " . $e->getLine());
    error_log("Trace: " . $e->getTraceAsString());
    
    // Determine response based on request type
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if ($isAjax || $requestMethod === 'POST') {
        // For AJAX or POST requests, return JSON error response
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ]);
        exit;
    }
    
    // For regular page loads, show error page
    if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
        echo "<h1>500 Internal Server Error</h1>";
        echo "<pre>";
        echo "Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
        echo "</pre>";
    } else {
        http_response_code(500);
        require __DIR__ . '/../views/500.php';
    }
    exit;
}