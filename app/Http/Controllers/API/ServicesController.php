<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ServiceFromRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{

    public function getAll(Request $request)
    {
        return Service::orderBy('created_at', 'desc')->get();
    }

    public function add(ServiceFromRequest $service)
    {
        try {
            Service::create($service->validated());
            return response()->json([
                'message' => 'Serivce created successfully !'
            ], 201);
        } catch (\Exception $err) {
            return $err;
        }
    }

    public function uploadImage(Request $request, string $id)
    {
        if ($request->hasFile('image')) {
            $service = Service::find($id);

            $previousPath = $service->image;

            $link = Storage::put('public/services', $request->file('image'));
            $service->update(['image' => $link]);

            if ($previousPath !== null) {
                Storage::delete($previousPath);
            }
            return response()->json(['message' => 'Service picture uploaded successfully!']);
        }
    }

    public function getOne(string $id)
    {
        $service = Service::find($id);
        $services = Service::get();
        $service['image'] = $service->imageUrl();

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json([
            'service' => $service,
            'services' => $services
        ], 200);
    }

    public function update(ServiceFromRequest $request, string $id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json(['message' => 'Service not found'], 404);
            }

            $service->update($request->validated());
        } catch (\Exception $err) {
            return $err;
        }
    }

    public function delete(string $id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json(['message' => 'Service not found'], 404);
            }

            $service->delete();
            return response()->json([
                'message' => 'Serivce removed successfully !'
            ], 200);
        } catch (\Exception $err) {
            return $err;
        }
    }
}
