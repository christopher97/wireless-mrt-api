<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Path;

class PathController extends Controller
{
    protected $path;

    public function __construct(Path $path) {
        $this->path = $path;
    }

    public function fetch() {
        $paths = $this->path->all();

        return response()->json(['paths' => $paths], 200);
    }

    public function find($id) {
        $path = $this->path->find($id);

        return response()->json(['path' => $path], 200);
    }
}
