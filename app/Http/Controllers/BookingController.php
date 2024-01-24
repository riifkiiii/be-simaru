<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ruangan;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Validator;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::with('ruangan')->with('user')->get());
    }

    public function indexPage()
    {
        $userRole = auth()->user()->role;
        $bookings = Booking::All();
        $ruangan = Ruangan::All();
        $user = User::All();
        return view('bookings/index', compact('bookings', 'userRole', 'ruangan', 'user'));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ruangan_id' => 'required',
            'user_id' => 'required',
            'start_book' => 'required',
            'end_book' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        if (Booking::isDatetimeAvailable($request->start_book, $request->end_book)) {
            $ruangan = Booking::create([
                'ruangan_id' => $request->ruangan_id,
                'user_id' => $request->user_id,
                'start_book' => $request->start_book,
                'end_book' => $request->end_book,
            ]);
            return response()->json([
                'message' => 'Booking Ruangan successfully created',
                'ruangan' => $ruangan
            ], 201);
        } else {
            return back()->with('error', 'Selected datetimes are not available');
        }
    }

    public function edit(Booking $booking)
    {
        return response()->json($booking);
    }

    public function update(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'ruangan_id' => 'required',
            'user_id' => 'required',
            'start_book' => 'required',
            'end_book' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $booking = Booking::where('id', $booking->id)->update([
            'ruangan_id' => $request->ruangan_id,
            'user_id' => $request->user_id,
            'start_book' => $request->start_book,
            'end_book' => $request->end_book,
        ]);
        if ($booking) {
            return response()->json([
                'message' => 'Booking successfully updated'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Failed to update'
            ], 201);
        }
    }

    public function delete($id)
    {
        $booking = Booking::where('id', $id)->first();
        $booking->delete();
        return response()->json([
            'message' => 'Booking successfully deleted',
        ], 201);
    }

    public function getBookedDates()
    {
        // Logic to fetch booked dates from the database
        $bookedDates = Booking::pluck('start_book')->toArray();

        return response()->json($bookedDates);
    }
}
