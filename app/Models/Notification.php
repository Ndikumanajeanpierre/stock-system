<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequisitionNotificationMail;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'link', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public static function send(int $userId, string $title, string $message, string $type = 'info', string $link = null): self
    {
        // Save system notification
        $notification = static::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => $link,
        ]);

        // Send email notification
        try {
            $user = User::find($userId);
            if ($user && $user->email) {
                Mail::to($user->email)->send(
                    new RequisitionNotificationMail($title, $message, $type)
                );
            }
        } catch (\Exception $e) {
            // Silently fail - don't break the app if email fails
        }

        return $notification;
    }
}