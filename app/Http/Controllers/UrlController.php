<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = DB::table('urls')->select('id', 'name', 'updated_at')->get();
        return view('index', ['urls' => $urls]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $token = $request->session()->token();
        $token = csrf_token();
        return view('home', ['test' => 'Test!!!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = parse_url($request->input('url.name'));
        $url['name'] = ($data['scheme'] ?? '') . '://' . ($data['host'] ?? '');
        $now = Carbon::now('Europe/Moscow');

        $validator = Validator::make($url, [
            'name' => 'url|unique:urls,name',
        ])->validate();

        DB::table('urls')->insert([
            'name' => $url['name'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        flash('URL добавлен!')->success();

        return redirect('/urls');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id > DB::table('urls')->count()) {
            abort(404);
        }
        [$url] = DB::table('urls')->select('id', 'name', 'updated_at')->where('id', '=', $id)->get()->all();
        return view('show', ['url' => $url]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }
}
