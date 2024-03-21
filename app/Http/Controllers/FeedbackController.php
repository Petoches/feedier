<?php

namespace App\Http\Controllers;

use App\Enums\FeedbackSource;
use App\Http\Requests\CreateFeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\FeedbackResource;

class FeedbackController extends Controller
{
    public function index() : \Inertia\Response
    {
        $feedback = Feedback::all();
        $archived = Feedback::onlyTrashed()->get();

        return Inertia::render('Admin/Feedback', [
            'feedback' => FeedbackResource::collection($feedback),
            'archivedFeedback' => FeedbackResource::collection($archived),
        ]);
    }

    public function create() : \Inertia\Response
    {
        return Inertia::render('Feedback');
    }

    public function store(CreateFeedbackRequest $request) : RedirectResponse
    {
        $user = auth()->user();

        $latestFeedback = $user ? $user->feedback()->latest()->first() : Feedback::where('email', $request->email)->latest()->first();

        if($latestFeedback && $latestFeedback->created_at->diffInHours(now()) === 0) {
            $request->session()->flash('flash.banner', 'You can send only one feedback per hour');
            $request->session()->flash('flash.bannerStyle', 'danger');
            return to_route('feedback');
        }

        Feedback::create([
            'content' => $request->content,
            'email' =>  $user ? $user->email : $request->email,
            'user_id' => $user?->id,
            'source' => FeedbackSource::Dashboard->name
        ]);

        $request->session()->flash('flash.banner', 'Your feedback was sended');
        $request->session()->flash('flash.bannerStyle', 'success');
        return to_route('feedback');
    }

    public function delete(Request $request): RedirectResponse
    {
        Feedback::find($request->feedbackId)->delete();
        return to_route('admin');
    }

    public function restore(Request $request): RedirectResponse
    {
        Feedback::withTrashed()->find($request->feedbackId)->restore();
        return to_route('admin');
    }
}
