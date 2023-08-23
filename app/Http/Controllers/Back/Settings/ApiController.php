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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $data = $this->validateTarget($request);

        $targetClass = $this->resolveTargetClass($data);

        if ($targetClass) {
            $ok = $targetClass->process($data);

            if ($ok) {
                return response()->json(['success' => $ok]);
            }
        }

        return response()->json(['error' => 'Whoops.!! Pokušajte ponovo ili kontaktirajte administratora!']);
    }


    /**
     * @param Request $request
     * @param string  $type
     *
     * @return mixed
     */
    public function validateTarget(Request $request, string $type = 'import')
    {
        $request->validate([
            'data.target' => 'required',
            'data.method' => 'required'
        ]);

        $data = $request->input('data');

        $request->merge([
            'data' => [
                'target' => $data['target'],
                'method' => $data['method'],
                'type' => $type
            ]
        ]);

        return $request->input('data');
    }


    /**
     * @param array $data
     *
     * @return mixed
     */
    private function resolveTargetClass(array $data)
    {
        $class = null;

        if (isset($data['target'])) {
            if ($data['target'] == 'akademska-knjiga-mk') {
                $class = new AkademskaKnjigaMk();
            }
        }

        return $class;
    }

}
