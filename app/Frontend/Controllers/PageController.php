<?php
namespace App\Http\Controllers\Frontend;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $pages = DB::table('pages')->where('slug', $slug)->get()->first();
        return view('frontend.pages', compact('pages'));
    }
}