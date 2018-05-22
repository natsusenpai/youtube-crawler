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
      $this->fetchYoutubeStatistics($channel->channel_id);
    }
  }

  public function fetchYoutubeStatistics($channelId) {
    
    $client = new \GuzzleHttp\Client();
    $apiKey = env("GOOGLE_SERVER_KEY");
    $res = $client->request('GET', "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$channelId&key=$apiKey");

    if ($res->getStatusCode() < 300) {
      try
      {
        $statistics = json_decode($res->getBody());
        YoutubeStatistics::insert(
          ['channel_id' => $channelId,
            'total_videos' => $statistics->items[0]->statistics->videoCount,
            'total_views' => $statistics->items[0]->statistics->viewCount,
            'total_subcribers' => $statistics->items[0]->statistics->subscriberCount,
            'unixtime' => time()
            ]
        );
      }
      catch(Exception $e)
      {
        echo "Cannot insert statistic, here is the problem: $e";
      }      
    }
    else {
      echo "Something went wrong!\n$res";
    }
  }
}
