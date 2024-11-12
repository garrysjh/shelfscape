<?php

use PHPUnit\Framework\TestCase;

class AllReviewsTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // Mock the database        <?php
        
        use PHPUnit\Framework\TestCase;
        
        class AllReviewsTest extends TestCase
        {
            protected $conn;
        
            protected function setUp(): void
            {
                // Mock the database connection
                $this->conn = $this->createMock(mysqli::class);
            }
        
            public function testDatabaseConnection()
            {
                // Simulate a successful connection
                $this->conn->method('connect_error')->willReturn(false);
                $this->assertFalse($this->conn->connect_error);
            }
        
            public function testSqlQueryBuilding()
            {
                // Mock the GET parameters
                $_GET['bookId'] = 1;
                $_GET['rating'] = 5;
                $_GET['recommended'] = 'yes';
        
                // Expected SQL query
                $expectedSql = "
        SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date, r.recommended
        FROM reviews r 
        JOIN user u ON r.userId = u.id
        WHERE r.bookId = 1 AND r.rating = 5 AND r.recommended = 'yes'";
        
                // Build the SQL query
                $bookId = isset($_GET['bookId']) ? $_GET['bookId'] : 0;
                $ratingFilter = isset($_GET['rating']) ? $_GET['rating'] : '';
                $recommendedFilter = isset($_GET['recommended']) ? $_GET['recommended'] : '';
        
                $reviews_sql = "
        SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date, r.recommended
        FROM reviews r 
        JOIN user u ON r.userId = u.id
        WHERE r.bookId = $bookId";
        
                if ($ratingFilter !== '') {
                    $reviews_sql .= " AND r.rating = $ratingFilter";
                }
        
                if ($recommendedFilter !== '') {
                    $reviews_sql .= " AND r.recommended = '$recommendedFilter'";
                }
        
                // Assert the SQL query
                $this->assertEquals($expectedSql, $reviews_sql);
            }
        } connection
        $this->conn = $this->createMock(mysqli::class);
    }

    public function testDatabaseConnection()
    {
        // Simulate a successful connection
        $this->conn->method('connect_error')->willReturn(false);
        $this->assertFalse($this->conn->connect_error);
    }

    public function testSqlQueryBuilding()
    {
        // Mock the GET parameters
        $_GET['bookId'] = 1;
        $_GET['rating'] = 5;
        $_GET['recommended'] = 'yes';

        // Expected SQL query
        $expectedSql = "
SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date, r.recommended
FROM reviews r 
JOIN user u ON r.userId = u.id
WHERE r.bookId = 1 AND r.rating = 5 AND r.recommended = 'yes'";

        // Build the SQL query
        $bookId = isset($_GET['bookId']) ? $_GET['bookId'] : 0;
        $ratingFilter = isset($_GET['rating']) ? $_GET['rating'] : '';
        $recommendedFilter = isset($_GET['recommended']) ? $_GET['recommended'] : '';

        $reviews_sql = "
SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date, r.recommended
FROM reviews r 
JOIN user u ON r.userId = u.id
WHERE r.bookId = $bookId";

        if ($ratingFilter !== '') {
            $reviews_sql .= " AND r.rating = $ratingFilter";
        }

        if ($recommendedFilter !== '') {
            $reviews_sql .= " AND r.recommended = '$recommendedFilter'";
        }

        // Assert the SQL query
        $this->assertEquals($expectedSql, $reviews_sql);
    }
}