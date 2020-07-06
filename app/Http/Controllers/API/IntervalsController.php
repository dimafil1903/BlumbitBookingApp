<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interval;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class IntervalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Interval[]|Collection
     */
    public function index(Request $request)
    {
        $month=$request->get('month');
        $year=$request->get('year');
        $date=$request->get('date');
        $prev=$request->get('prev');
        $next=$request->get('next');
        if($prev && $next){
            $monthIntervals=Interval::where('year',$year)->where('month',$month)->where('booked',0)->get();
            $collection=new Collection();
            for ($i=1;$i<=$prev;$i++){
                $collection=Interval::where('year',$year)->where('month',$month-$i)->where('booked',0)->get();
                foreach ($collection as $item){
                    $monthIntervals->add($item);
                }

            }
            for ($i=1;$i<=$next;$i++){
                $collection=Interval::where('year',$year)->where('month',$month+$i)->where('booked',0)->get();
                foreach ($collection as $item){
                    $monthIntervals->add($item);
                }
            }

            return $monthIntervals;
        }
        if(!$date){
            return Interval::where('year',$year)->where('month',$month)->get();
        }else{
            return Interval::where('year',$year)->where('month',$month)->where('date',$date)->get();
        }


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
            'intervals' => 'required',
        ]);

        $intervals=$request['intervals'];

        $del=Interval::where('date',$intervals[0]['date'])->
        where('month',$intervals[0]['month'])->
        where('year',$intervals[0]['year'])->withTrashed()->get();
        sleep(0.5);
        if (!empty($intervals)){
            foreach ($intervals as $interval){
//                var_dump($intervals);
                if (isset($interval['id'])){
                    $i=Interval::find($interval['id']);
                    $i->date=$interval['date'];
                    $i->month = $interval['month'];
                    $i->year = $interval['year'];
                    $i->from = $interval['from'];
                    $i->to = $interval['to'];
                    $i->booked = $interval['booked'];
                    $i->save();
                }else{
                    if($interval['from'] && $interval['to']) {

                        Interval::Create([
                            'date' => $interval['date'],
                            'month' => $interval['month'],
                            'year' => $interval['year'],
                            'from' => $interval['from'],
                            'to' => $interval['to'],
//                            'booked' => $interval['booked'],

                        ]);
                    }
                }
            }
        }

        return response(null, Response::HTTP_OK);


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
        $interval = Interval::findOrFail($id);
        $this->validate($request, [
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $interval->date = $request['date'];
        $interval->month = $request['month'];
        $interval->year = $request['year'];
        $interval->from = $request['from'];
        $interval->to = $request['to'];

        $interval->save();

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
       $i= Interval::find($id);
       $i->forceDelete();
        return response(null,Response::HTTP_OK);
    }
}
