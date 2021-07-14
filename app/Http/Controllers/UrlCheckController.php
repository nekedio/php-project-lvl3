<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class UrlCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $id, Request $request)
    {
        [$url] = DB::table('urls')->select('*')->where('id', '=', $id)->get()->all();

        try {
            $response = Http::get($url->name);
            $document = new Document($response->body());

            $h1 = trim(optional($document->first("h1"))->text()) ?? "-";
            $description = optional($document->first("meta[name=description]"))->attr('content') ?? "-";
            $keywords = optional($document->first("meta[name=keywords]"))->attr('content') ?? "-";
            $now = Carbon::now('Europe/Moscow');
            $status_code = $response->status();

            DB::table('url_checks')->insert([
                'url_id' => $id,
                'status_code' => $status_code,
                'h1' => $h1,
                'keywords' => $keywords,
                'description' => $description,
                'updated_at' => $now,
                'created_at' => $now,
            ]);
            flash('Страница успешно проверена!')->success();
            return redirect()->route('urls.show', $url->id);
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('urls.show', $url->id);
        }
    }
}
