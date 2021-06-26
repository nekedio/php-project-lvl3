<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

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
            return redirect()->route('showUrl', ['id' => $url->id]);
        }

        DB::table('urls')->insert([
            'name' => $url['name'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        flash('URL добавлен!')->success();

        return redirect()->route('urls');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeChecks($id, Request $request)
    {
        [$url] = DB::table('urls')->select('*')->where('id', '=', $id)->get()->all();

        $now = Carbon::now('Europe/Moscow');
        $response = Http::get($url->name);
        $status_code = $response->status();
        if ($response->body() == 'test') {
            $h1 = $response->header('h1');
            $keywords = $response->header('keywords');
            $description = $response->header('description');
        } else {
            $document = new Document($url->name, true);
            $h1 = trim(optional($document->first("h1"))->text()) ?? "-";
            $description = optional($document->first("meta[name=description]"))->attr('content') ?? "-";
            $keywords = optional($document->first("meta[name=keywords]"))->attr('content') ?? "-";
        }

        DB::table('urls_checks')->insert([
            'url_id' => $id,
            'status_code' => $status_code,
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description,
            'updated_at' => $now,
            'created_at' => $now,
        ]);
        flash('Страница успешно проверена!')->success();
        return redirect()->route('showUrl', ['id' => $url->id]);
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

        return view('show', ['url' => $url, 'url_checks' => $url_checks]);
    }
}
