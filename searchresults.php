<?php
// Mockup data (replace this with your actual data retrieval logic)
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$searchResults = getSearchResults($searchQuery);

// Function to simulate search results (replace this with your actual data retrieval logic)
function getSearchResults($query) {
    // Perform your search logic here (e.g., querying a database)
    // Return an array of results (in a real scenario, you might fetch data from a database)
    $results = array(
        "Result 1 for $query",
        "Result 2 for $query",
        "Result 3 for $query",
    );

    return $results;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Add any styling as needed */
        body {
            font-family: 'Century Gothic';
            text-align: center;
            margin: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px;
        }
    </style>
</head>
<body>
    <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

    <?php if (!empty($searchResults)): ?>
        <ul>
            <?php foreach ($searchResults as $result): ?>
                <li><?php echo htmlspecialchars($result); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
    <?php endif; ?>
</body>
</html>
