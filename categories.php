<?php
require_once 'lib/wikimedia.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if (empty($data->category)) {
        echo json_encode(array("error" => "No Category Submitted"));
    }
    else{
        $categoryName = trim($data->category);
        $articles = Wikimedia::getArticlesReadabilityByCategoryName($categoryName);
        echo json_encode(array("articles" => $articles));
    }
}