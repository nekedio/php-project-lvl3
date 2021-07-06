<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class CheckController extends Controller
{
    public function store($id, Request $request)
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
    }
}
