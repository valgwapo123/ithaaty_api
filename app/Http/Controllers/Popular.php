<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserChannel;
use App\Models\UserPodcast;
use App\Models\Audio;

class Popular extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function Searchpopular_Channel(Request $request){
       
        // $fields=$request->validate([
        //     'setup_ownerid'=>'required|string',
        // ]);

         $search = '%'.$request->input('searchdata').'%';




        $Userchannel= UserChannel::where('channel_typestatus','active')
        ->where('channel_name', 'like', $search)->take(5)->get();





        return response($Userchannel, 201);



     }   
       public function Searchpopular_Episode(Request $request){

        //   $fields=$request->validate([
        //     'channel_id'=>'required|string',
        // ]);



         $search = '%'.$request->input('searchdata').'%';
        



         $UserAudio= DB::table('audio')
        ->join('user_podcast_episodes', 'user_podcast_episodes.poditem_audioid', '=', 'audio.id')
        ->join('user_podcasts', 'user_podcasts.id', '=', 'user_podcast_episodes.poditem_podcastid')
        ->join('user_channels', 'user_channels.id', '=', 'user_podcasts.podcast_channelid')
        ->select('audio.audio_name','audio.audio_season','user_channels.channel_name','user_podcasts.podcast_title','audio.audio_path','audio.audio_type')
        ->take(5)->get();

        // $UserAudio= Audio::where('audio_name', 'like', $search)->take(10)->get();


//->join('user_podcasts', 'user_podcasts .poditem_audioid', '=', 'audio.id')


        return response($UserAudio, 201);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
