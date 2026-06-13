<?php
declare(strict_types=1);

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Lead;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\ProcessLeadJob;
use Illuminate\Support\Facades\Log; 

class LeadController extends Controller
{
    // fetch data from the lead model and pass to router 
    public function index() {     
        // get() to fetch all        
        // if timestamp are identical or very close, sort by id
        $leads = Lead::orderBy('created_at', 'desc')->orderBy('id','asc')->paginate(10);

        return view('leads.main', ["leads" => $leads]);
    }

    public function show(string $id) {
        $lead = Lead::findOrFail($id);
        return view('leads.show', ["lead" => $lead]);
    }

    // Request injects incoming HTTP request to perform operations 
    // create function used by admin route
    public function create(Request $request) {
        $query = Lead::orderBy('created_at', 'desc')->orderBy('id', 'asc');

        // check if innput field exissts and is not empty
        if ($request->filled('status')) { 
            $query->where('status', $request->status);
        }
        
        $leads = $query->paginate(10)->withQueryString(); // append active filters for status

        return view('leads.admin', [
            'leads'=> $leads, 
            'statusFilter'=> $request->status,
        ]);
    }

    public function updateStatus(Request $request, string $id) 
    {
        // must contain status key, else 422 unprocessable content
        $request->validate([
            // no spaces for in: rule. or vailidation will fail
            'status' => ['required', 'in:qualified,disqualified,under_review'],        
        ]);
        // if not found, 404 error
        $lead = Lead::findOrFail($id);

        Log::info('updateStatus called', [
            'id' => $id,
            'requested_status' => $request->status,
            'current_status' => $lead->status,
        ]);

        // update sql command
        $lead->update(['status' => $request->status]);

        Log::info('updateStatus after update', [
            'status_now' => $lead->fresh()->status,
        ]);

        return redirect()->route('leads.admin')
            ->with('success', "Lead #{$id} status updated to {$lead->status}");
    }

    public function store(StoreLeadRequest $request) {
        // json returns for Postman single data ingestion
        ProcessLeadJob::dispatch($request->validated());
        return response()->json([
            'message' => 'Lead received and being processed in the background',
        ], 202);
    }

    public function destroy() {
      // handle delete request 
    }
}
