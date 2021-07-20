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
        $urls = DB::table('urls')->get();
        $checks = DB::table('url_checks')->get();

        $lastChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('MAX(created_at) AS last_check_at'))
            ->groupBy('url_id', 'status_code')
            ->get();

        $collectUrl = collect($urls);
        $collectChecks = collect($lastChecks);

        $collectUrl->map(function ($item, $key) use ($collectChecks) {
            $lastCheck = $collectChecks->firstWhere('url_id', $item->id);
            $item->status_code = $lastCheck->status_code ?? null;
            $item->last_check_at = $lastCheck->last_check_at ?? null;
            return;
        });

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
        $data = $request->validate(['url.name' => 'url']);

        $urlName = $data['url']['name'];
        $componentsUrl = parse_url(strtolower($urlName));
        $scheme = $componentsUrl['scheme'] ?? '';
        $host = $componentsUrl['host'] ?? '';
        $name = "{$scheme}://{$host}";

        $nameInDB = DB::table('urls')
            ->where('name', $name)
            ->first();
        if ($nameInDB) {
            flash('URL уже есть!')->success();
            return redirect()->route('urls.show', $nameInDB->id);
        }

        $now = Carbon::now('Europe/Moscow');
        DB::table('urls')->insert([
            'name' => strtolower($name),
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
        $url = DB::table('urls')->find($id);

        abort_unless((bool) $url, 404);

        $url_checks = DB::table('url_checks')->select(
            'id',
            'status_code',
            'h1',
            'keywords',
            'description',
            'created_at'
        )->where('url_id', $id)->get();

        return view('show', ['url' => $url, 'url_checks' => $url_checks]);
    }
}
