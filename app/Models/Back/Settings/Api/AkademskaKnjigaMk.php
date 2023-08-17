<?php

namespace App\Models\Back\Settings\Api;

use App\Models\Back\Settings\Settings;
use Illuminate\Http\Request;

class AkademskaKnjigaMk
{

    /**
     * @var Request
     */
    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function process()
    {

    }

}
