<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
set_time_limit(0);
ini_set('max_execution_time', '0');
class youtubeDLController extends Controller
{
    public function index(){
        if($_POST["playlistID"]){
            $playlistID = $_POST["playlistID"];
        }else{
            return 0;
        }
        $testas = $this->downloadOriginal($playlistID);
    

        
        return 1;
    }

    public function downloadContents($ID){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.youtube.com/playlist?list=" . $ID);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }
    public function checkStatus($task){
        if(preg_match('~Finished\sdownloading\splaylist~msi', $task)){
            return true;
        }
        else{
            return false;
        }
    }
    public function downloadOriginal($ID){
        $cmd = 'youtube-dl -i --extract-audio --audio-format mp3 -o "C:\wamp64\www\downloader\downloader\downloads\%(title)s.%(ext)s"  https://www.youtube.com/playlist?list=' . $ID;
        $task = shell_exec($cmd);

        return $task;
    }

    public function convertAudio($name, $external){
        $convertCMD = "ffmpeg -i \"C:\wamp64\www\downloader\downloader\downloads\\" . $name . "." . $external . "\" " . "\"C:\wamp64\www\downloader\downloader\downloads\\" . $name . ".mp3\"";
        $convertTask = shell_exec($convertCMD);
        echo $convertCMD;

        return $convertTask;
    }

    public function deleteOriginal($name, $external){
        $deleteCMD = "del /f \"C:\wamp64\www\downloader\downloader\downloads\\" . $name . "." . $external . "\"";
        $deleteTask = shell_exec($deleteCMD);

        return $deleteTask;
    }

}
