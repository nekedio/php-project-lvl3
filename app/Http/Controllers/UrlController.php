<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $urls = DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select(
                'urls.id',
                'urls.name',
                DB::raw('MAX(url_checks.created_at) AS created_at'),
                'url_checks.status_code'
            )
            ->groupBy('urls.id', 'url_checks.status_code')
            ->orderBy('urls.id')
            ->get();
        return view('index', ['urls' => $urls]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = parse_url($request->input('url.name'));
        $url = [];
        $url['name'] = ($data['scheme'] ?? '') . '://' . ($data['host'] ?? '');
        $now = Carbon::now('Europe/Moscow');

        $validatorUrl = Validator::make($url, [
            'name' => 'url',
        ])->validate();

        $validatorUnique = Validator::make($url, [
            'name' => 'unique:urls,name',
        ]);
        if ($validatorUnique->fails()) {
            [$url] = DB::table('urls')
                ->select('*')
                ->where('name', '=', $url['name'])->get()->all();
            flash('URL уже есть!')->success();
            return redirect()->route('urls.show', $url->id);
        }

        DB::table('urls')->insert([
            'name' => $url['name'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        flash('URL добавлен!')->success();

        return redirect()->route('urls.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if ($id > DB::table('urls')->count()) {
            abort(404);
        }
        [$url] = DB::table('urls')
            ->select('id', 'name', 'updated_at', 'created_at')
            ->where('id', '=', $id)->get()->all();
        $url_checks = DB::table('url_checks')->select(
            'id',
            'status_code',
            'h1',
            'keywords',
            'description',
            'created_at'
        )->where('url_id', '=', $id)->get()->all();

        return view('show', ['url' => $url, 'url_checks' => $url_checks]);
    }
}
