<?php
//https://api.ultramsg.com/instance/messages?token=&page=1&limit=100
new ultraMsgChatBot("xa69niactamdsx7u", "90023");

class ultraMsgChatBot
{
    var $client;
    public function __construct($ultramsg_token, $instance_id)
    {
        require_once('ultramsg.class.php'); //Latest 2.0.3 stable
        require_once('ultramsg-dictionary.php');

        $ultramsgDictionary = new ultramsgDictionary();
        $this->client = new UltraMsg\WhatsAppApi($ultramsg_token, $instance_id);

        $json = file_get_contents('php://input');
        $decoded = json_decode($json, true);
        ob_start();
        var_dump($decoded);
        $input = ob_get_contents();
        ob_end_clean();
        file_put_contents('requests.log', $input . PHP_EOL, FILE_APPEND);

        if (isset($decoded['data'])) {
            $message = $decoded['data'];
            $text = $this->convert($message['body']);

            if (!$message['fromMe']) {
    $to = $message['from'];
    $val = mb_strtolower($text, 'UTF-8');

    if (in_array($val, $ultramsgDictionary->welcomeIntent())) {
        $randMsg = $ultramsgDictionary->welcomeResponses();
        $this->client->sendChatMessage($to, $randMsg);
    } else if ($val === '1') {
        $this->client->sendChatMessage($to, date('d.m.Y H:i:s'));
    } else if ($val === '2') {
        $this->client->sendImageMessage($to, "https://file-example.s3-accelerate.amazonaws.com/images/test.jpg", "image Caption");
    } else if ($val === '3') {
        $this->client->sendDocumentMessage($to, "cv.pdf", "https://file-example.s3-accelerate.amazonaws.com/documents/cv.pdf");
    } else if ($val === '4') {
        $this->client->sendAudioMessage($to, "https://file-example.s3-accelerate.amazonaws.com/audio/2.mp3");
    } else if ($val === '5') {
        $this->client->sendVoiceMessage($to, "https://file-example.s3-accelerate.amazonaws.com/voice/oog_example.ogg");
    } else if ($val === '6') {
        $this->client->sendVideoMessage($to, "https://file-example.s3-accelerate.amazonaws.com/video/test.mp4", "c");
    } else if ($val === '7') {
        $this->client->sendContactMessage($to, "14000000001@c.us");
    } else if ($val === '8') {
        $this->client->sendChatMessage($to, $ultramsgDictionary->generateRandomSentence());
    } else if ($val === '9') {
        $this->client->sendChatMessage($to, $ultramsgDictionary->generateRandomJoke());
    } else if ($val === '10') {
        $this->client->sendImageMessage($to, "Random Image", $ultramsgDictionary->generateRandomImage());
    } else if (substr($val, 0, 6) === 'tiktok') {
    $url = trim(substr($text, 7)); // Extract the URL after 'tiktok'
    
    if (empty($url)){
        $this->client->sendChatMessage($to, "No URL provided.");
    }
     else {
        include('TikTokHandler.php');
        
    }
}

 else {
        $this->welcome($message['from'], true);  // Welcome or error handling
    }
}

        }
    }

private function getTikTokVideoUrl($url) {
        @unlink('cookie.txt');
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
            $video_url = str_replace('\u002F', '/', $matches[1]);
            return $this->downloadTikTokVideo($video_url);
        } else {
            return false;
        }
    }

    private function downloadTikTokVideo($video_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $video_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $videoData = curl_exec($ch);
        curl_close($ch);

        if (!$videoData) {
            return "Failed to download video.";
        }

        $filename = 'downloads/video_' . time() . '.mp4';  // Saving the file with a unique name based on current time
        file_put_contents($filename, $videoData);

        return $filename;  // Return the path where the video was saved
    }


private function getState($userId) {
    $stateFile = "user_state_$userId.txt";
    if (file_exists($stateFile)) {
        return file_get_contents($stateFile);
    }
    return null;
}

private function setState($userId, $state) {
    $stateFile = "user_state_$userId.txt";
    file_put_contents($stateFile, $state);
}

private function clearState($userId) {
    $stateFile = "user_state_$userId.txt";
    if (file_exists($stateFile)) {
        unlink($stateFile);
    }
}

    public function welcome($to, $noWelcome = false)
    {
        /*$welcomeStr = ($noWelcome) ? "```📢 Incorrect command 📢 ```\nPlease type one of these *commands*:\n" : "welcome to ultramsg bot Demo \n";
        $this->client->sendChatMessage(
            $to,
            $welcomeStr .
                "\n" .
                "1️⃣ : Show server time.\n" .
                "2️⃣ : Send Image.\n" .
                "3️⃣ : Send Document.\n" .
                "4️⃣ : Send Audio.\n" .
                "5️⃣ : Send Voice.\n" .
                "6️⃣ : Send Video.\n" .
                "7️⃣ : Send Contact.\n" .
                "8️⃣ : Send Random Sentence.\n" .
                "9️⃣ : Send Random Joke.\n" .
                "🔟 : Send Random Image.\n".
                "11 : Fetch Score From Mzanzi game.\n"

        );*/
        //$ima = file_get_contents('test.png');
        $this->client->sendImageMessage($to, "https://67.207.90.206/GyomeiBo", "*Namu Amida Butsu 😭🙏📿, my name is Gyomei Himejima, and I only exist to serve people 😭🙏📿*\n\n*• Below are my commands •*\n\n-------------------------------------------\n*🤖 Command* - *ℹ️ Description*\n\n*```tiktok```* - *Download TikTok Videos.*\n*Usage:* ```tiktok https://vm.tiktok.com/ZMrDESddN/```\n\n*```chat```* - *Chat with ChatGPT.*\n*Usage:* ```chat how many days can a human being fast?```\n\n*```song```* - *Download music.*\n\n*Usage:* ```song https://open.spotify.com/track/6Kijtp0DB6VwcoJIw7PJ9W```\n\n*• About my creator •*\n*🆔 Facebook:* `FlashKidd`\n*🆔 Telegram:* `theFlashxD`");
    }
    private function fetchLeaderboardData() {
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://lucky.mzansigaming.com/');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$html = curl_exec($ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $leaderboard = "";
    for ($i = 1; $i <= 10; $i++) {
        $playerNameQuery = "//div[contains(@class, 'rank-$i')]//h4";
        $playerScoreQuery = "//div[contains(@class, 'rank-$i')]//p[contains(text(), 'Score')]";

        $playerNameNode = $xpath->query($playerNameQuery);
        $playerName = $playerNameNode->length > 0 ? trim($playerNameNode->item(0)->nodeValue) : "Not available";

        $playerScoreNode = $xpath->query($playerScoreQuery);
        $playerScore = $playerScoreNode->length > 0 ? trim(str_replace("Score:", "", $playerScoreNode->item(0)->nodeValue)) : "Not available";

        $leaderboard .= "Player " . $i . " Name: " . $playerName . " - Score: " . $playerScore . "\n";
    }
    return $leaderboard;
}


    //convert Arabic/Persian numbers to English 
    public function convert($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);
        return $englishNumbersOnly;
    }
}
