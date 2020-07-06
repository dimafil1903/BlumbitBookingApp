<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interval;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PersonsController extends Controller
{
    public function index()
    {
        return Person::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',

        ]);

        return Person::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',

        ]);
        $person->date = $request['name'];
        $person->month = $request['email'];
        $person->year = $request['phone'];

        $person->save();

        return response(null, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Person::destroy($id);
        return response(null,Response::HTTP_OK);
    }
}
