<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view("admin.projects.index", compact("projects"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.projects.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|max:30",
            "description" => "max:600"
        ], [
            "title.required" => "Il titolo è necessario!",
            "title.max" => "La lunghezza massima è di 600 caratteri!"
        ]);
        $data = $request->all();
        $newProject = new Project();
        $newProject->fill($data);
        $newProject->slug = Str::slug($newProject->title);
        // dd($newProject);

        $newProject->save();

        return redirect()->route("admin.projects.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $project = Project::where("slug", $slug)->first();
        return view("admin.projects.show", compact("project"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $project = Project::where("slug", $slug)->first();
        return view("admin.projects.edit", compact("project"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $request->validate([
            "title" => "required|max:30",
            "description" => "max:600"
        ], [
            "title.required" => "Il titolo è necessario!",
            "title.max" => "La lunghezza massima è di 600 caratteri!"
        ]);
        $project = Project::where("slug", $slug)->first();

        $data = $request->all();

        $project->update($data);
        return redirect()->route("admin.projects.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route("admin.projects.index")->with("message", "Il progetto " . $project->title . " è stato eliminato con successo!");
    }
}
