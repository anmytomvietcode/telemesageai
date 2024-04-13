<?php

class Telegram_API {
  private $bot_token;

  public function __construct( $bot_token ) {
    $this->bot_token = $bot_token;
  }

  public function send_message( $chat_id, $text, $user_id = null ) {
    $data = array(
      'chat_id' => $chat_id, 
      'text' => $text
    );
    
    // Gắn thêm thông tin người dùng nếu có
    if ( $user_id ) {
      $data['reply_markup'] = json_encode( array(
        'force_reply' => true,
        'input_field_placeholder' => 'Trả lời...'
      ));
    }
    
    return $this->make_request( 'sendMessage', $data );
  }

  public function set_webhook( $url ) {
    return $this->make_request( 'setWebhook', array( 'url' => $url ) ); 
  }

  private function make_request( $method, $data ) {
    $url = 'https://api.telegram.org/bot' . $this->bot_token . '/' . $method;
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $response = curl_exec( $ch );
    curl_close( $ch );
    return json_decode( $response, true );
  }
}
