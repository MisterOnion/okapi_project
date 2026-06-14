<?php
declare(strict_types=1);

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

use App\Models\Lead;
use App\Models\Audit;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\ProcessLeadJob;
use App\Services\LeadAuditService;


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

    public function showAudit() {
        // pass $logs variable to view
        $logs = Audit::with('lead')->orderBy('changed_at', 'desc')->paginate(20);
        return view('leads.audit', ['logs' => $logs]);
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

        // read old status data, then update new status data
        $oldStatus = $lead->status;
        $lead->update(['status' => $request->status]);
        
        app(LeadAuditService::class)->logStatusChange($lead, $oldStatus, $request->status);

        Log::info('updateStatus after update', [
            'status_now' => $lead->fresh()->status,
        ]);

        return redirect()->route('leads.admin')
            ->with('success', "Lead #{$id} status updated to {$lead->status}");
    }

    public function update(Request $request, string $id)
    {
        // check if inline form has the data, else process stops
        $request->validate([
            'customer_name'              => ['required', 'string', 'max:255'],
            'email'                      => ['required', 'email', 'max:255'],
            'phone_number'               => ['required', 'string', 'max:20'],
            'monthly_electricity_bill_rm'=> ['required', 'numeric', 'min:0'],
            'state'                      => ['required', 'string'],
            'property_type'              => ['required', 'in:landed,condo,apartment,commercial'],
            'roof_type'                  => ['required', 'in:tile,metal,flat,concrete'],
        ]);

        $lead = Lead::findOrFail($id);

        $oldValues = $lead->only([
            'customer_name', 'email', 'phone_number',
            'monthly_electricity_bill_rm', 'state', 'property_type', 'roof_type'
        ]);
        
        $updateData = ($request->only([
            'customer_name', 'email', 'phone_number',
            'monthly_electricity_bill_rm', 'state', 'property_type', 'roof_type'
        ]));

        $lead->update($updateData);

        app(LeadAuditService::class)->logFieldChanges($lead, $oldValues, $updateData);

        return redirect()->route('leads.admin')
            ->with('success', "Lead #{$id} has been updated successfully");
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
