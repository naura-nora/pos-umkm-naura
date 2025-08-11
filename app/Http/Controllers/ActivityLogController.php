<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
{
    $activities = Activity::with(['causer', 'subject'])
        ->when($request->search, function($query) use ($request) {
            return $query->where('description', 'like', '%'.$request->search.'%')
                ->orWhere('event', 'like', '%'.$request->search.'%')
                ->orWhereHas('causer', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                      ->orWhere('email', 'like', '%'.$request->search.'%');
                })
                ->orWhereHas('subject', function($q) use ($request) {
                    if (method_exists($q->getModel(), 'getActivitylogOptions')) {
                        $logOptions = $q->getModel()->getActivitylogOptions();
                        foreach ($logOptions->logAttributes as $attribute) {
                            $q->orWhere($attribute, 'like', '%'.$request->search.'%');
                        }
                    }
                });
        })
        ->latest()
        ->paginate(15);
        
    return view('activity-log.index', compact('activities'));
}
}