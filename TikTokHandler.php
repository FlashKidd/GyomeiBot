<?php
@unlink('cookie.txt');

function getTikTokVideoUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $response = curl_exec($ch);
    curl_close($ch);

    if (preg_match('/"UrlList":\["(.*?)"/', $response, $matches)) {
        return str_replace('\u002F', '/', $matches[1]);
    } else {
        return false;
    }
}

//if (isset($_GET['tiktok_url'])) {
    //$url = $_GET['tiktok_url'];
    $direct_video_url = getTikTokVideoUrl($url);

    if ($direct_video_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $direct_video_url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // Directly transfer the binary data
        curl_setopt($ch, CURLOPT_HEADER, false); // Don't include the header in the output
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        $video_data = curl_exec($ch);
        curl_close($ch);

        if ($video_data !== false) {
            $filename = 'downloads/tiktok_' . time() . '.mp4';

            file_put_contents($filename, $video_data);
            $link = "http://67.207.90.206/GyomeiBo/$filename";
            $this->client->sendVideoMessage($to,$link,"Video downloaded successfully."); 
            @unlink($filename);
        } else {
            $this->client->sendChatMessage($to,"Failed to download video content. Please try again.");
        }
    } else {
        $this->client->sendChatMessage($to, "Failed to retrieve the video URL.");
    }

?>
