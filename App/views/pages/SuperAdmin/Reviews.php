<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="Reviews/Reviews.css">
</head>
<body>
    <button onclick="goBack()" class="back-button">Back</button>
    <h2>Reviews</h2>

    <table>
        <tr>
            <th>ReviewID</th>
            <th>UserID</th>
            <th>Review</th>
            <th>Date Time</th>
            <th>BusID</th>
        </tr>

        <?php
        // Array of reviews
        $reviews = [
            ['reviewId' => 1, 'userId' => 1001, 'review' => 'Great service! Very comfortable seats.', 'dateTime' => '2024-11-10 08:30:00', 'busId' => 2001, 'replied' => false],
            ['reviewId' => 2, 'userId' => 1002, 'review' => 'Driver was late by 10 minutes but overall good experience.', 'dateTime' => '2024-11-11 10:00:00', 'busId' => 2002, 'replied' => true],
            ['reviewId' => 3, 'userId' => 1003, 'review' => 'Bus was clean and on time. Highly recommend!', 'dateTime' => '2024-11-12 15:45:00', 'busId' => 2003, 'replied' => false]
        ];

        // Loop through the reviews array and display each review
        foreach ($reviews as $review) {
            $rowClass = !$review['replied'] ? 'new-review' : '';
            echo "<tr onclick=\"goToReplyPage({$review['reviewId']})\" class=\"$rowClass\">";
            echo "<td>{$review['reviewId']}</td>";
            echo "<td>{$review['userId']}</td>";
            echo "<td>{$review['review']}</td>";
            echo "<td>{$review['dateTime']}</td>";
            echo "<td>{$review['busId']}</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script src="Reviews/Reviews.js"></script>
</body>
</html>
