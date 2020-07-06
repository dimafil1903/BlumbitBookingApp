<?php


namespace App\Http\Controllers\API;


use App\BookedMeeting;
use App\Http\Controllers\Controller;
use App\Interval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BookedMeetingsController extends Controller
{

    public function index(Request $request)
    {
        $meetings = BookedMeeting::orderBy('id', 'desc')->paginate(15);
        foreach ($meetings as $meeting) {
            $interval = Interval::find($meeting->interval_id);
            $meeting->interval = $interval;
        }
        return $meetings;
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required',
                'type' => 'required',
                'description' => 'required',

            ]);
        } catch (ValidationException $e) {

        }
        if($request['type']==='user') {

            try {
                $this->validate($request, [
                    'interval_id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',

                ]);
            } catch (ValidationException $e) {
                return response($e->getMessage(), Response::HTTP_EXPECTATION_FAILED);
            }
            if ($request['interval_id']) {
                $interval = Interval::find($request['interval_id']);
                $interval->booked = 1;
                $interval->save();

            }
//        dd( ( $request['title']));
            BookedMeeting::Create([
                'type' => $request['type'],
                'title' => $request['title'],
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'description' => $request['description'],
                'interval_id' => $request['interval_id'],

            ]);
        }elseif ($request['type']==='admin'){
            try {
                $this->validate($request, [
                    'from' => 'required',
                    'to' => 'required',
                    'month' => 'required',
                    'year' => 'required',
                    'date' => 'required',

                ]);
            } catch (ValidationException $e) {

            }
        $createdInterval=    Interval::Create([
            'from' => $request['from'],
            'to' => $request['to'],
            'month' => $request['month'],
            'year' => $request['year'],
            'date' => $request['date'],
            'booked' => "1",

            ]);
            BookedMeeting::Create([
                'type' => $request['type'],
                'title' => $request['title'],
                'invites' => $request['invites'],
                'custom_invites' => $request['custom_invites'],
                'description' => $request['description'],
                'interval_id' => "$createdInterval->id",


            ]);
            var_dump($createdInterval);
        }
        return response(null, Response::HTTP_OK);

//           ТУТ БУДЕТ ОТПРАВКА ИМЕЙЛА О ТОМ ЧТО ДАННАЯ ВСТРЕЧА ЗАБИНДЕНА
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'interval' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response($e->getMessage(), Response::HTTP_EXPECTATION_FAILED);
        }
        $meeting = BookedMeeting::find($id);
        $interval = Interval::find($meeting->interval_id);
        $interval->from = $request["interval"]["from"];
        $interval->to = $request["interval"]["to"];

        $interval->month = $request["interval"]["month"];
        $interval->year = $request["interval"]["year"];
        $interval->date = $request["interval"]["date"];
        $interval->save();


//           ТУТ БУДЕТ ОТПРАВКА ИМЕЙЛА ОБ ИЗМЕНЕНИИ ВРЕМЕНИ
    }

    public function destroy($id)
    {
        BookedMeeting::destroy($id);
        return response(null, Response::HTTP_OK);
    }
}
