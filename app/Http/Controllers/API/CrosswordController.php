<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CrosswordResource;
use App\Models\Crossword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CrosswordController extends Controller
{
    function createWordByVert(array $crossword, array $word, $rec = 0, $max_rec = 20): array
    {
        if ($rec == $max_rec) return [$crossword, false];

        $y_size = count($crossword);
        $x_size = count($crossword[0]);


        # Word coordinates
        $x = rand(0, $x_size - 1);
        $y = rand(0, $y_size - count($word));


        # Check if coordinates are free or can be crossed by current word
        for ($i = 0; $i < count($word); $i++) {
            if ($crossword[$y + $i][$x][1] == 1) {
                if ($crossword[$y + $i][$x][0] == $word[$i]) {
                    continue;
                } else {
                    return $this->createWordByHor($crossword, $word, $rec + 1);
                }
            }
        }

        # Inserting word
        for ($i = 0; $i < count($word); $i++) {
            $crossword[$y + $i][$x] = [$word[$i], 1];
        }


        return [$crossword, $word];
    }

    function createWordByHor(array $crossword, array $word, $rec = 0, $max_rec = 20): array
    {
        if ($rec == $max_rec) return [$crossword, false];

        $y_size = count($crossword);
        $x_size = count($crossword[0]);

        # Word coordinates
        $x = rand(0, $x_size - count($word));
        $y = rand(0, $y_size - 1);


        # Check if coordinates are free or can be crossed by current word
        for ($i = 0; $i < count($word); $i++) {
            if ($crossword[$y][$x + $i][1] == 1) {
                if ($crossword[$y][$x + $i][0] == $word[$i]) {
                    continue;
                } else {
                    return $this->createWordByVert($crossword, $word, $rec + 1);
                }

            }
        }

        # Inserting word
        for ($i = 0; $i < count($word); $i++) {
            $crossword[$y][$x + $i] = [$word[$i], 1];
        }


        return [$crossword, $word];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:crosswords',
            'words' => 'required|array',
            'x' => 'integer',
            'y' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        # Size by default
        if (!isset($request->x)) {
            $request->x = 10;
        }
        if (!isset($request->y)) {
            $request->y = 10;
        }

        $crossword = [];
        $words_list = [];
        $chars = mb_str_split("йцукенгшщзхъфывапролджэячсмитьбю");


        # Generating crossword and filling with random char
        for ($i = 0; $i < $request->y; $i++) {
            $crossword[$i] = [];
            for ($j = 0; $j < $request->x; $j++) {
                $crossword[$i][$j] = [$chars[rand(0, count($chars) - 1)], 0];
            }
        }


        # Filling created crossword with words
        for ($i = 0; $i < count($request->words); $i++) {

            if (rand(0, 1)) {
                $result = $this->createWordByVert($crossword, mb_str_split($request->words[$i]));

                $crossword = $result[0];
                if ($result[1]) {
                    $word = implode($result[1]);
                    $words_list[] = $word;
                }
            } else {
                $result = $this->createWordByHor($crossword, mb_str_split($request->words[$i]));

                $crossword = $result[0];

                if ($result[1]) {
                    $word = implode($result[1]);
                    $words_list[] = $word;
                }


            }
        }

        $crossword_saved = Crossword::create([
            'name' => $request->name,
            'crossword' => json_encode($crossword),
            'size' => json_encode([$request->x, $request->y]),
            'user_id' => Auth::id(),
            'words' => json_encode($words_list),
        ]);

        return response()->json(new CrosswordResource($crossword_saved));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:crosswords,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $crossword = Crossword::query()->where('id', $id)->first();

        return response()->json(new CrosswordResource($crossword));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
