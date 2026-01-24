<?php

$urls = [
    'https://www.youtube.com/live/rPYg8jo9q1Y?si=xpZQTvUpFdqvPnG0',
    'https://www.youtube.com/watch?v=rPYg8jo9q1Y',
    'https://youtu.be/rPYg8jo9q1Y',
];

$pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i';

foreach ($urls as $url) {
    echo "Testing: $url\n";
    if (preg_match($pattern, $url, $match)) {
        echo "Match: " . $match[1] . "\n";
    } else {
        echo "No Match\n";
    }
    echo "----------------\n";
}
