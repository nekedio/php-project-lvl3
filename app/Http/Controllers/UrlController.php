<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = DB::table('urls')
            ->leftJoin('urls_checks', 'urls.id', '=', 'urls_checks.url_id')
            ->select(
                'urls.id',
                'urls.name',
                DB::raw('MAX(urls_checks.created_at) AS created_at'),
                'urls_checks.status_code'
            )
            ->groupBy('urls.id', 'urls_checks.status_code')
            ->orderBy('urls.id')
            ->get();
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
        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUrl(Request $request)
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeChecks($id, Request $request)
    {
        // dump($response);
        [$url] = DB::table('urls')->select('*')->where('id', '=', $id)->get()->all();
        $response = Http::get($url->name);
        $status_code = $response->status();
        // $status_code = 200;
        $now = Carbon::now('Europe/Moscow');

        DB::table('urls_checks')->insert([
            'url_id' => $id,
            'status_code' => $status_code,
            'h1' => 'h1',
            'keywords' => 'keywosrds',
            'description' => 'description',
            'updated_at' => $now,
            'created_at' => $now,
        ]);
        return redirect('/urls/' . $id);
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
        [$url] = DB::table('urls')
            ->select('id', 'name', 'updated_at', 'created_at')
            ->where('id', '=', $id)->get()->all();
        $url_checks = DB::table('urls_checks')->select(
            'id',
            'status_code',
            'h1',
            'keywords',
            'description',
            'created_at'
        )->where('url_id', '=', $id)->get()->all();

        // dump($url_check);

        return view('show', ['url' => $url, 'url_checks' => $url_checks]);
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
