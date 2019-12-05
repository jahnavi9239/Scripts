#!/bin/bash

#
# Script to keep downloading YouTube videos to your computer using youtube-dl:
# http://rg3.github.io/youtube-dl/
#
# Put it to work:
# Place this file in the folder you want to download and use command 'sh youtube-downloader.sh' in that terminal path
# Run this script enter URL, starting video number to download, format

#

echo "\n Hello user, Please give specific inputs to download video from youtube \n"

read -p "`echo '\n> '`video/playlist url: " url

read -p "`echo '\n> '`video number to start download(hit enter to start from first): " s_no

read -p  "`echo '\n> '`Quality format(eg: 240/480/720/1080)(hit enter for best available format): " format

echo "\n\nSit back and relax, download in progress....\n\n" 

s_no=${s_no:=1} #setting default start video value
format=${format:=1080} #setting default format value

#defining downlad function
download_video(){
  #install youtube.dl if not installed in the system
  if [ $(dpkg-query -W -f='${Status}' youtube-dl 2>/dev/null | grep -c "ok installed") -eq 0 ];
  then
    echo "Installing 'youtube.dl' a software required to download videos..."
    sudo apt-get install youtube-dl;
  fi

  if [ $(dpkg-query -W -f='${Status}' speech-dispatcher 2>/dev/null | grep -c "ok installed") -eq 0 ];
  then
    echo "Installing 'speech-dispatcher to give a voice output after download complete"
    sudo apt-get install speech-dispatcher;
  fi

  youtube-dl --playlist-start "$2" -f 'bestvideo[height<='"$3"']+bestaudio/best[height<='"$3"']' "$1" && echo "\n\nDownload Completed!" && spd-say "Download completed"
}


#invoking download function
download_video $url $s_no $format
