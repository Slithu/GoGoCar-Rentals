<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreCarRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        Artisan::call('update:car-availability');

        $search = $request->input('search');

        $query = Car::with('availability');

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->where('brand', 'like', "%{$term}%")
                            ->orWhere('model', 'like', "%{$term}%");
                    });
                }
            });
        }

        $cars = $query->paginate(7);

        return view('cars.index', [
            'cars' => $cars,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view("cars.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request) : RedirectResponse
    {
        $car = new Car($request->validated());
        if ($request->hasFile('image')) {
            $car->image_path = Storage::disk('public')->put('cars', $request->file('image'));
        }
        $car->save();
        return redirect(route('cars.index'))->with('status', 'Car stored!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car) : View
    {
        return view("cars.show", [
            'car' => $car
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car) : View
    {
        $lastSelectedCarBody = $car->car_body;
        $lastSelectedEngineType = $car->engine_type;
        $lastSelectedTransmission = $car->transmission;

        return view('cars.edit', [
            'car' => $car,
            'lastSelectedCarBody' => $lastSelectedCarBody,
            'lastSelectedEngineType' => $lastSelectedEngineType,
            'lastSelectedTransmission' => $lastSelectedTransmission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCarRequest $request, Car $car) : RedirectResponse
    {
        $car->fill($request->validated());
        if ($request->hasFile('image')) {
            $car->image_path = Storage::disk('public')->put('cars', $request->file('image'));
        }
        $car->save();
        return redirect(route('cars.index'))->with('status', 'Car updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //$car = Car::find($car);
        $car->delete();
        return redirect(route('cars.index'))->with('status', 'Car deleted!');
    }

    public function detail(Car $car)
    {
        $reviews = Review::where('car_id', $car->id)->get();

        return view('cars.detail', [
            'car' => $car,
            'reviews' => $reviews,
            'user' => User::all()
        ]);
    }
}
