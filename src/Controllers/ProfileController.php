<?php

namespace HealthTracker\Controllers;

use HealthTracker\Models\Profile;
use HealthTracker\Utils\Render;

class ProfileController {
    private $profile;

    public function __construct() {
        $this->profile = new Profile();
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /albi/login');
            exit;
        }
        
        Render::render('profile/index', [
            'title' => 'Profil Saya - HealthTracker',
            'currentPage' => 'Profil'
        ]);
    }

    public function getProfile() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            $profile = $this->profile->getByUserId($_SESSION['user']);
            echo json_encode($profile ?? []);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $success = $this->profile->update($_SESSION['user'], $data);
            
            if ($success) {
                echo json_encode(['message' => 'Profile updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Failed to update profile']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function uploadPhoto() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            if (!isset($_FILES['photo'])) {
                throw new \Exception('No file uploaded');
            }

            $file = $_FILES['photo'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileError = $file['error'];

            if ($fileError !== UPLOAD_ERR_OK) {
                throw new \Exception('Upload failed with error code: ' . $fileError);
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($fileTmpName);
            if (!in_array($fileType, $allowedTypes)) {
                throw new \Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
            }

            // Generate unique filename
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $extension;
            $uploadPath = __DIR__ . '/../../public/uploads/profile/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move file to uploads directory
            if (!move_uploaded_file($fileTmpName, $uploadPath . $newFileName)) {
                throw new \Exception('Failed to move uploaded file');
            }

            // Update profile with new photo URL
            $photoUrl = '/albi/uploads/profile/' . $newFileName;
            $success = $this->profile->updatePhoto($_SESSION['user'], $photoUrl);

            if ($success) {
                echo json_encode([
                    'message' => 'Photo uploaded successfully',
                    'photo_url' => $photoUrl
                ]);
            } else {
                unlink($uploadPath . $newFileName);
                throw new \Exception('Failed to update profile photo');
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 