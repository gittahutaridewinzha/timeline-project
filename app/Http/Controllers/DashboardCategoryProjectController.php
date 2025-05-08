<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use Illuminate\Http\Request;

class DashboardCategoryProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = CategoryProject::all();
        return view("back-end.pages.kategori-project.index" , compact("kategori"));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string",
        ]);

        CategoryProject::create([
            "name"=> $request->name
        ]);

        return redirect()->route("category-project.index")->with("success","data berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name"=> "required|string",
            ]);


        $kategori = CategoryProject::findOrFail($id);
        $kategori->name = $request->name;

        $kategori->save();

        return redirect()->route("category-project.index")->with("success","data berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = CategoryProject::findOrFail($id);
        $kategori->delete();

        return redirect()->route("category-project.index")->with("success","data berhasil dihapus");
    }
}
