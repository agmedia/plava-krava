<?php

namespace App\Http\Controllers\Back\Settings;

use App\Http\Controllers\Controller;
use App\Models\Back\Settings\Api\AkademskaKnjigaMk;
use App\Models\Back\Settings\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('back.settings.api.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        Log::info($request);

        $this->validate($request);
        $request->merge(['type' => 'import']);

        $targetClass = $this->resolveTargetClass($request);

        $ok = $targetClass->process();

        if ($ok) {
            return response()->json(['success' => 'Application basic info is saved.']);
        }

        return response()->json(['error' => 'Whoops.!! Pokušajte ponovo ili kontaktirajte administratora!']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::info($request);

        $updated = 1;

        if ($updated) {
            return response()->json(['success' => 'Application basic info is saved.']);
        }

        return response()->json(['error' => 'Whoops.!! Pokušajte ponovo ili kontaktirajte administratora!']);
    }


    /**
     * @param Request $request
     *
     * @return array
     */
    private function validate(Request $request)
    {
        return $request->validate([
            'target' => 'required',
            'method' => 'required'
        ]);
    }


    private function resolveTargetClass(Request $request)
    {
        $class = new \stdClass();

        if ($request->input('target') == '') {
            $class = new AkademskaKnjigaMk($request);
        }

        return $class;
    }

}
