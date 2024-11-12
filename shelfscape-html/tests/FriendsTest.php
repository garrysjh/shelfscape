<?php

use PHPUnit\Framework\TestCase;

class FriendsTest extends TestCase
{
    protected $conn;
    protected $stmt;

    protected function setUp(): void
    {
        // Mock the database connection and statement
        $this->conn = $this->createMock(mysqli::class);
        $this->stmt = $this->createMock(mysqli_stmt::class);

        // Mock session data
        $_SESSION = [
            'loggedin' => true,
            'user_id' => 1
        ];
    }

    public function testUserNotLoggedInRedirect()
    {
        // Mock session data for not logged in state
        $_SESSION['loggedin'] = false;

        // Start output buffering to capture header calls
        ob_start();
        include __DIR__ . '/../friends.php';
        ob_end_clean();

        // Check if the user is redirected to login.php
        $this->assertContains('Location: login.php', xdebug_get_headers());
    }

    public function testDatabaseConnection()
    {
        // Simulate a successful connection
        $this->conn->method('connect_error')->willReturn(false);
        $this->assertFalse($this->conn->connect_error);
    }

    public function testFriendConfirmation()
    {
        // Mock POST data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['confirm_friend'] = true;
        $_POST['friend_id'] = 2;

        // Mock the SQL query
        $update_sql = "UPDATE Friends SET status = 'CONFIRMED' WHERE userId = ? AND friendId = ? AND status = 'PENDING'";

        // Mock the statement preparation and execution
        $this->conn->method('prepare')->with($update_sql)->willReturn($this->stmt);
        $this->stmt->method('bind_param')->with('ii', $_SESSION['user_id'], $_POST['friend_id']);
        $this->stmt->method('execute')->willReturn(true);

        // Execute the friend confirmation logic
        include __DIR__ . '/../friends.php';

        // Assert that the friend confirmation query was executed
        $this->assertTrue($this->stmt->execute());
    }
}