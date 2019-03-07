<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/categories.css">
    </head>
    <body>
        <div id="container">
            <h1>Wikimedia Task</h1>
            <form id="categoryForm" action="categories.php" method="post">
                <label for="category">Category:</label> <input type="text" id="category" name="category_name">
                <input type="submit" value="Submit">
            </form>
            <div id="articlesList"></div>
        </div>
        <script src="js/categories.js"></script> 
    </body>
</html> 