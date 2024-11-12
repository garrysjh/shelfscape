<?php

use PHPUnit\Framework\TestCase;

class DonateTest extends TestCase
{
    protected $htmlContent;

    protected function setUp(): void
    {
        // Mock session data
        $_SESSION = [
            'loggedin' => true,
            'profilePicture' => 'assets/icons/user.png',
            'user_id' => 1
        ];

        // Load the HTML content from the donate.php file
        ob_start();
        include __DIR__ . '/../donate.php';
        $this->htmlContent = ob_get_clean();
    }

    public function testUserIconExistsWhenLoggedIn()
    {
        // Check if the user icon is present when logged in
        $this->assertStringContainsString('<img src="assets/icons/user.png" alt="User Icon" class="usericon"/>', $this->htmlContent);
    }

    public function testDropdownContentExistsWhenLoggedIn()
    {
        // Check if the dropdown content is present when logged in
        $this->assertStringContainsString('<div class="dropdown-content login-dropdown-content">', $this->htmlContent);
        $this->assertStringContainsString('<a href="profile.php?id=1">Profile</a>', $this->htmlContent);
        $this->assertStringContainsString('<a href="friends.php">Friends</a>', $this->htmlContent);
        $this->assertStringContainsString('<a href="cart.php">Cart</a>', $this->htmlContent);
        $this->assertStringContainsString('<a href="settings.php">Settings</a>', $this->htmlContent);
        $this->assertStringContainsString('<a href="logout.php">Logout</a>', $this->htmlContent);
    }

    public function testLoginLinkExistsWhenNotLoggedIn()
    {
        // Mock session data for not logged in state
        $_SESSION['loggedin'] = false;

        // Load the HTML content from the donate.php file
        ob_start();
        include __DIR__ . '/../donate.php';
        $htmlContent = ob_get_clean();

        // Check if the login link is present when not logged in
        $this->assertStringContainsString('<a href="login.php">', $htmlContent);
        $this->assertStringContainsString('<img src="assets/icons/user.png" alt="User Icon" class="usericon"/>', $htmlContent);
    }

    public function testDonateFormExistsWhenLoggedIn()
    {
        // Check if the donate form is present when logged in
        $this->assertStringContainsString('<h2>Donate a Book</h2>', $this->htmlContent);
        $this->assertStringContainsString('<form action="donate_process.php" method="POST">', $this->htmlContent);
        $this->assertStringContainsString('<label for="title">Title:</label>', $this->htmlContent);
        $this->assertStringContainsString('<input type="text" id="title" name="title" required placeholder="Title of the book">', $this->htmlContent);
        $this->assertStringContainsString('<label for="author">Author:</label>', $this->htmlContent);
        $this->assertStringContainsString('<input type="text" id="author" name="author" required placeholder="Author of the book">', $this->htmlContent);
        $this->assertStringContainsString('<label for="description">Description:</label>', $this->htmlContent);
        $this->assertStringContainsString('<textarea id="description" name="description" required placeholder="Book description"></textarea>', $this->htmlContent);
    }
}