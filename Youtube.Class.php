<?php

Class YouTube {

        private $channel;

        public function __construct($name){
                $this->channel = $name;
        }
        public function getLastVideo() {
                $json = file_get_contents("http://gdata.youtube.com/feeds/api/users/".$this->channel."/uploads?alt=json");
        $data = json_decode($json, true);
        $video = $data['feed']['entry'][0];

            $r['videos'] = $data['feed']['entry'];
                $pub = $video['published']['$t'];
                $date = explode("T", $pub);
                $date_eu = explode("-", $date[0]);
                $time = explode(":", $date[1]);
                $r['publishedDate'] = $date[0];
                $r['publishedDateEU'] = $date_eu[2].'.'.$date_eu[1].'.'.$date_eu[0];
                $r['publishedTime'] = $time[0].':'.$time[1];

                $r['title'] = $video['title']['$t'];
                $r['description'] = $video['media$group']['media$description']['$t'];
                $r['views'] = $video['yt$statistics']['viewCount'];

                $rating['amount'] = $video['gd$rating']['average'];
                $r['percentage'] = round($rating['amount'] * 20, 1);
                if ($r['percentage'] > 100) {
                $r['percentage'] = 100;
                }
                if ($r['percentage'] == 0) {
                $r['percentage'] = 'Keine';
                }
                $r['author'] = ucfirst(strtolower($video['author'][0]['name']['$t']));
                $id = explode("/",$video['id']['$t']);
                $r['id'] = $id[count($id) -1];
                foreach($video['media$group']['media$thumbnail'] as $thumbnail){
                        if($thumbnail['height']==90 && $thumbnail['width'] == 120){
                                $r['image'] = $thumbnail['url'];
                                break;
                        }
                }
                $seconds = $video['media$group']['media$content'][0]['duration'];
                $duration = floor($seconds/60).':'.($seconds % 60);
                $r['duration'] = $duration;
        return $r;
        }
}
?>
