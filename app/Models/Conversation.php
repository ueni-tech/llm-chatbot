<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenAI;

class Conversation extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'title'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function messages()
  {
    return $this->hasMany(Message::class);
  }

  public function getMessageHistory()
  {
    return $this->messages()
      ->orderBy('created_at', 'asc')
      ->take(10)
      ->get()
      ->map(function ($message) {
        return [
          'role' => $message->role,
          'content' => $message->content,
        ];
      })
      ->toArray();
  }
}
