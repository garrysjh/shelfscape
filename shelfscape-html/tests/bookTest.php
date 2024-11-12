<?php

use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    protected $conn;
    protected $stmt;

    protected function setUp(): void
    {
        // Mock the database connection and statement
        $this->conn = $this->createMock(mysqli::class);
        $this->stmt = $this->createMock(mysqli_stmt::class);
    }

    public function testReviewQueryExecution()
    {
        // Mock the GET parameter
        $bookId = 1;

        // Mock the SQL query
        $reviews_sql = "
SELECT r.rating, r.date 
FROM reviews r 
WHERE r.bookId = ? 
ORDER BY r.date DESC 
LIMIT 3";

        // Mock the statement preparation and execution
        $this->conn->method('prepare')->with($reviews_sql)->willReturn($this->stmt);
        $this->stmt->method('bind_param')->with('s', $bookId);
        $this->stmt->method('execute')->willReturn(true);

        // Mock the result set
        $result = $this->createMock(mysqli_result::class);
        $this->stmt->method('get_result')->willReturn($result);

        // Mock the fetch_assoc method
        $result->method('fetch_assoc')->willReturnOnConsecutiveCalls(
            ['rating' => 5, 'date' => '2023-01-01'],
            ['rating' => 4, 'date' => '2023-01-02'],
            null
        );

        // Execute the query
        $stmt = $this->conn->prepare($reviews_sql);
        $stmt->bind_param('s', $bookId);
        $stmt->execute();
        $reviews_result = $stmt->get_result();

        // Fetch the results
        $reviews = [];
        $total_rating = 0;
        $review_count = 0;
        while ($row = $reviews_result->fetch_assoc()) {
            $reviews[] = $row;
            $total_rating += $row['rating'];
            $review_count++;
        }
        $stmt->close();

        // Calculate average rating
        $average_rating = $review_count > 0 ? $total_rating / $review_count : 0;

        // Assert the results
        $this->assertCount(2, $reviews);
        $this->assertEquals(4.5, $average_rating);
    }

    public function testAllBooksAverageRating()
    {
        // Mock the SQL query
        $all_books_sql = "
SELECT AVG(rating) AS avg_rating 
FROM reviews";

        // Mock the statement preparation and execution
        $this->conn->method('prepare')->with($all_books_sql)->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);

        // Mock the result set
        $result = $this->createMock(mysqli_result::class);
        $this->stmt->method('get_result')->willReturn($result);

        // Mock the fetch_assoc method
        $result->method('fetch_assoc')->willReturn(['avg_rating' => 4.2]);

        // Execute the query
        $stmt = $this->conn->prepare($all_books_sql);
        $stmt->execute();
        $all_books_result = $stmt->get_result();
        $all_books_avg_rating = $all_books_result->fetch_assoc()['avg_rating'];
        $stmt->close();

        // Assert the average rating
        $this->assertEquals(4.2, $all_books_avg_rating);
    }
}