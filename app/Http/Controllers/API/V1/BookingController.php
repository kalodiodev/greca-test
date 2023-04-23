<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookProductRequest;
use App\Http\Resources\V1\Bookings;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    /**
     * Index bookings
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return Bookings::collection(Booking::withAvailability()->get());
    }

    /**
     * Submit new booking
     *
     * @param BookProductRequest $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|JsonResponse|\Illuminate\Http\Response
     */
    public function store(BookProductRequest $request)
    {
        /** @var Product $product */
        $product = Product::with('bookings')
            ->where('id', $request->product_id)
            ->first();

        if (! $product->isAvailable()) {
            return response()->json([
                'message' => 'Product is not available'
            ], Response::HTTP_PRECONDITION_FAILED);
        }

        Booking::create([
            'client_id' => $request->client_id,
            'product_id' => $request->product_id,
            'booked_on' => $request->booked_on
        ]);

        return response(null, Response::HTTP_CREATED);
    }
}
