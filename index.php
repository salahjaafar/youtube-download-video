// رابط الفيديو الذي تريد تحميله
$video_url = 'https://www.youtube.com/watch?v=VIDEO_ID_HERE';

// استدعاء ال API الخاص بـ YouTube
$api_url = 'https://www.youtube.com/get_video_info?video_id=' . getVideoId($video_url);

// استدعاء البيانات من ال API
$api_data = file_get_contents($api_url);

// تحويل البيانات إلى صيغة مصفوفة (Array)
parse_str($api_data, $video_info);

// حفظ رابط الفيديو
$video_url = '';
if(isset($video_info['url_encoded_fmt_stream_map'])){
    $formats = explode(',', $video_info['url_encoded_fmt_stream_map']);
    if(count($formats)){
        $format = explode('&', $formats[0]);
        foreach($format as $value){
            $pair = explode('=', $value);
            if($pair[0] == 'url'){
                $video_url = urldecode($pair[1]);
                break;
            }
        }
    }
}

// بدء تحميل الفيديو
if($video_url != ''){
    header("Content-Disposition: attachment; filename=\"" . basename($video_url) . "\"");
    readfile($video_url);
}

// تابع لاستخراج معرف الفيديو من الرابط
function getVideoId($video_url) {
    $query_string = parse_url($video_url, PHP_URL_QUERY);
    parse_str($query_string, $query_arr);
    return isset($query_arr['v']) ? $query_arr['v'] : '';
}
