
<?php

use PHPUnit\Framework\TestCase;

class AboutUsTest extends TestCase
{
    protected $htmlContent;

    protected function setUp(): void
    {
        // Load the HTML content from the aboutus.php file
        $this->htmlContent = file_get_contents(__DIR__ . '/../aboutus.php');
    }

    public function testProfileImageExists()
    {
        // Check if the profile image is present
        $this->assertStringContainsString('<img src="assets/icons/josiah_garry.png"', $this->htmlContent);
    }

    public function testOurStorySectionExists()
    {
        // Check if the "Our Story" section is present
        $this->assertStringContainsString('<div class="our-story">', $this->htmlContent);
        $this->assertStringContainsString('<h3>Our Story</h3>', $this->htmlContent);
        $this->assertStringContainsString('<img src="assets/icons/ntu_campus.png"', $this->htmlContent);
    }

    public function testBirthOfShelfScapeSectionExists()
    {
        // Check if the "The Birth of ShelfScape" section is present
        $this->assertStringContainsString('<div class="birth-of-shelfscape interactive-card">', $this->htmlContent);
        $this->assertStringContainsString('<h3>The Birth of ShelfScape</h3>', $this->htmlContent);
        $this->assertStringContainsString('<img src="assets/icons/coding_sessions.png"', $this->htmlContent);
    }

    public function testOurMissionSectionExists()
    {
        // Check if the "Our Mission" section is present
        $this->assertStringContainsString('<div class="our-mission">', $this->htmlContent);
        $this->assertStringContainsString('<h3>Our Mission</h3>', $this->htmlContent);
    }
}
    public function testSessionHandling()
    {
        // Test when the user is not logged in
        $_SESSION['loggedin'] = false;
    }
}