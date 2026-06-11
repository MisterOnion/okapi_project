<?php
declare(strict_types=1);

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Lead;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\ProcessLeadJob;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function index() {
        // route --> /leads/
        // fetch all data from the lead model and pass to index view 
        // $leads = Lead::orderBy('created_at', 'desc')->get();
        // $leads = Lead::orderBy('created_at', 'desc')->paginate(10);

        // if timestamp are identical or very close, sort by id
        $leads = Lead::orderBy('created_at', 'desc')->orderBy('id','asc')->paginate(10);

        return view('leads.main', ["leads" => $leads]);
    }

    public function show(string $id) {
        // route --> /leads/{id}
        // fetch a single record & pass into show view.
        $lead = Lead::findOrFail($id);
        return view('leads.show', ["lead" => $lead]);
    }

    public function create() {
        // route --> /leads/create
        // render a create view (with web form) to users
        return view('leads.admin');
    }

    public function store(StoreLeadRequest $request) {
        // --> /ninjas/ (POST)
        // handle POST request to store a new ninja record in table
        
        // validate method
        // dd($request->validated());  dump and die for debugging

        ProcessLeadJob::dispatch($request->validated());
        return response()->json([
            'message' => 'Lead received and being processed in the background',
        ], 202);
    }

    public function destroy() {
      // --> /ninjas/{id} (DELETE)
      // handle delete request to delete a ninja record from table
      // $ninja = Ninja::findOrFail($id); 
      // no need to manually search and delete that ninja id, laravel handles that for us
      // called wrapped model binding

    }

    // edit() and update() for edit view and update requests
    // we won't be using these routes
}
