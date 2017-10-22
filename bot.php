<?php
set_time_limit(0);
function makeCurl($api,$method,$datas=[]){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot{$api}/{$method}");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query($datas));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    return $server_output;
}

$last_updated_id=0;
function getUpdates(){
    global $last_updated_id;
    $updates = json_decode(
        makeCurl("405390903:AAEPTuenGamFVDMhvYK_3MF8CBXa08aHsQQ","getUpdates",[
            "offset"=>($last_updated_id+1)
        ])
    );
    if($updates->ok == true && count($updates->result) > 0){
        foreach($updates->result as $update){
            $last_updated_id = $update->update_id;
            $chat_id = $update->message->chat->id;
            $message_id = $update->message->message_id;
            $text= "پیام شما رسید .";
            makeCurl("405390903:AAEPTuenGamFVDMhvYK_3MF8CBXa08aHsQQ","sendMessage",[
               "chat_id"=>$chat_id,
                "reply_to_message_id"=>$message_id,
                "text"=>$text
            ]);
        }
    }

    sleep(5);
    getUpdates();
}
getUpdates();
