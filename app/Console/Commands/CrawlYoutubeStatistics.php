<?php

namespace App\Console\Commands;

use App\Channel;
use App\YoutubeStatistics;

use Illuminate\Console\Command;

class CrawlYoutubeStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'YoutubeStatistics:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allChannels = Channel::all();
        foreach($allChannels as $channel) {
            CrawlYoutubeStatistics::fetchYoutubeStatistics($channel->channel_id);
        }
    }

    public static function fetchYoutubeStatistics($channelId) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$channelId&key=AIzaSyDDvJ_tXXsFVXbXfyM4CeI-etDmKUFHnLY",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $statistics = json_decode($response);
            YoutubeStatistics::insert(
                ['channel_id' => $channelId,
                 'total_videos' => $statistics->items[0]->statistics->videoCount,
                 'total_views' => $statistics->items[0]->statistics->viewCount,
                 'total_subcribers' => $statistics->items[0]->statistics->subscriberCount,
                 'unixtime' => time()
                 ]
            );
        }
    }
}
