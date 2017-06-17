<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use session;
use App\Author;

class BooksController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::with('author');
            return Datatables::of($books)->addColumn('action', function($book){
                return view('datatable._action', [
                    'model'=>$book,
                    'form_url'=>route('books.destroy', $book->id),
                    'edit_url'=>route('books.edit', $book->id),
                    'confirm_message'=>'Yakin Mau Menghapus' .$book->title. '?'
                    ]);
            })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data'=>'title', 'name'=>'title', 'title'=>'Judul'])
        ->addColumn(['data'=>'amount', 'name'=>'amount', 'title'=>'Jumlah'])
        ->addColumn(['data'=>'author.name', 'name'=>'author.name', 'title'=>'Penulis'])
        ->addColumn(['data'=>'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('books.index')->with(compact('html'));
	}

/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title'  =>'required|unique:books,title',
            'author_id'   =>'required|exists:authors,id',
            'amount'   =>'required|numeric',
            'cover'   =>'image|max:2048'
            ]);
    
    $book = Book::create($request->except('cover'));

        if ($request->hasFile('cover')){
            $uploaded_cover = $request->file('cover');

            $extension = $uploaded_cover->getClientOriginalExtension();

            $filename = md5(time()).'.'. $extension;

            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            $book->cover = $filename;
            $book->save();
        }
        session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"berhasil menyiman $book->title"
        ]);
        return redirect()->route('bboks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $author = Author::find($id);
        return view('authors.edit')->with(compact('author'));
        $book = Book::find($id);
        return view('books.edit')-with(compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, ['name'=>'required|unique:authors,name,'.$id]);
        $author = Author::find($id);
        $author->update($request->only('name'));
        Session::flash("flash_notification", ["level"=>"success", "message"=>"Berhasil menyimpan $author->name"]);
        return redirect()->route('authors.index');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(!Author::destroy($id)) return redirect()->back();

        Session::flash("flash_notification", ["level"=>"success", "message"=>"Penulis Berhasil Dihapus"]);
        return redirect()->route('authors.index'); 
    }
}