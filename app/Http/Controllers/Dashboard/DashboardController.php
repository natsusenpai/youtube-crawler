<?php

namespace App\Http\Controllers;

use App\YoutubeStatistics;
use App\Channel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{   
  public function getDashboardSummary(Request $request) {
      
    $result = [];

    $startTime = $request->startTime ?? 0;
      $endTime = $request->endTime ?? time();
    $channelName = Channel::select('channel_name')
      ->where('channel_id',$request->channelId)
      ->get()->first()->channel_name;
    
    $chartData = YoutubeStatistics::select('total_videos as totalVideos', 'total_views as totalViews', 'total_subcribers as totalSubcribers', 'total_likes as totalLikes', 'unixtime')
      ->where('channel_id', $request->channelId)
      ->whereBetween('unixtime', [$startTime, $endTime])
      ->orderBy('id', 'asc')
      ->get();

    $result = [
      'channelName' => $channelName,
      'diffVideo' => $chartData->last()->totalVideos - $chartData->first()->totalVideos,
      'diffView' => $chartData->last()->totalViews - $chartData->first()->totalViews,
      'diffSubcriber' => $chartData->last()->totalSubcribers - $chartData->first()->totalSubcribers,
      'diffLike' => $chartData->last()->totalLikes - $chartData->first()->totalLikes,
      'chartData' => $chartData
    ];
    return $result;
  }
}
