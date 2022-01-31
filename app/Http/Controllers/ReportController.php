<?php

namespace App\Http\Controllers;

use App\Models\report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function UploadReport(Request $request)
    {
        
        $request->validate([
            'file' => 'required|max:10000|mimes:pdf,doc,docx'
        ]);
        $name = $request->file('file')->getClientOriginalName();
        $path =  $request->file('file')->storeAs('uploads', $name, 'public');
        $file = new report();
        $file->emp_id = request('emp_id');
        $file->lab_id = request('lab_id');
        $file->fileName = $name;
        $file->path = $path;
        $file->save();
        return redirect()->back()->with('success', 'Learning Material Uploaded Successfully');
    }
    public function deletePatient(Request $request, $id)
    {
        
    }
}
