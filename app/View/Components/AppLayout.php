<?php

namespace App\View\Components;

use App\Models\Conversation;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $conversations;

    public function __construct()
    {
        $this->conversations = Conversation::where('user_id', auth()->id())->get();
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app', [
            'conversations' => $this->conversations,
        ]);
    }
}
