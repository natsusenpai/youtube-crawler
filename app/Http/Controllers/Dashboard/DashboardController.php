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
        
        $chartData = YoutubeStatistics::select('total_videos', 'total_views', 'total_subcribers', 'total_likes', 'unixtime')
            ->where('channel_id', $request->channelId)
            ->whereBetween('unixtime', [$startTime, $endTime])
            ->orderBy('id', 'desc')
            ->get();

        $result = [
            'channel_name' => $channelName,
            'diff_video' => $chartData->first()->total_videos - $chartData->last()->total_videos,
            'diff_view' => $chartData->first()->total_views - $chartData->last()->total_views,
            'diff_sub' => $chartData->first()->total_subcribers - $chartData->last()->total_subcribers,
            'diff_like' => $chartData->first()->total_likes - $chartData->last()->total_likes,
            'chartData' => $chartData
        ];
        return $result;
    }
    
    
}
