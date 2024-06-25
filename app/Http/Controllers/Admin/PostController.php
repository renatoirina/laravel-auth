<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $postsArray = Post::all();

        return view('admin.posts.index', compact('postsArray'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view("admin.posts.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) {
        // dd($request->all());
        // $request->validate([
        //     'title' => ['required', 'max:255', 'min:5', 'unique:posts'],
        //     'content' => ['nullable', 'min:10', 'max: 5000']
        // ]);
        // $data = $request->all();
        $data = $request->validated();

        // Controllo se c'è il file di cover_image nel request
        if ($request->hasFile('cover_image')) {
            // Salvo il file nel storage
            $image_path = Storage::put('post_images', $request->cover_image);
            // salvo il path del file nei dati da inserire nel daabase
            $data['cover_image'] = $image_path;

            /**
             * $data = [
             *  'title' => 'titolo,
             *  'content' => 'contenuto',
             *  'cover_image' => 'percorso immagine'
             * ]
             */
        }


        $post = new Post();
        $post->fill($data);
        $post->slug = Str::slug($request->title);
        $post->save();

        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {
        return view('admin.posts.show', compact('post'));
    }


    /**
     * Display the specified resource. Senza dependency injection
     */
    // public function show(string $slug)
    // {
    //     $post = Post::where('slug', $slug)->first();
    //     if(!$post) {
    //         abort(404);
    //     }
    //     dd($post);
    //     return view('admin.posts.show', compact('id'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post) {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post) {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);

        // Se nel request c'è il file dell'immagine
        //  Se il post aveva l'immagine
        //      la cancello
        //  salvo la nuova immagine 
        //  salvo il percorso nei data da aggiornare
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::delete($post->cover_image);
            }
            $image_path = Storage::put('post_images', $request->cover_image);
            $data['cover_image'] = $image_path;
        }


        $post->update($data);
        return redirect()->route('admin.posts.show', $post->slug)->with('message', 'post ' . $post->title . ' è stato modificato');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        // Se il post ha l'immagine, cancelliamola
        if ($post->cover_image) {
            Storage::delete($post->cover_image);
        }

        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', 'post ' . $post->title . ' è stato cancellato');
    }
}
