<?php
header('Content-Type: application/json');

$rss_url = "https://www.bookbrowse.com/rss/book_news.rss";

$rss_feed = file_get_contents($rss_url);

if ($rss_feed === FALSE) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch RSS feed']);
    exit;
}

$xml = simplexml_load_string($rss_feed);
$json = json_encode($xml);

echo $json;
