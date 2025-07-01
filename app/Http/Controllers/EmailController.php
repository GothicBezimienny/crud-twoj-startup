<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Http\Requests\EmailRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Email::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailRequest $request): JsonResponse
    {
        try {
            $email = Email::create($request->validated());
        } catch (\Throwable) {
            Log::error('Failed to create email');

            return response()->json(status: RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($email, RESPONSE::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email): Email
    {
        return $email;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailRequest $request, Email $email): JsonResponse
    {
        $email->update($request->validated());

        return response()->json($email);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email): JsonResponse
    {
        try {
            $email->delete();
        } catch (\Throwable) {
            Log::error('Failed to remove email');

            new HttpException(RESPONSE::HTTP_BAD_GATEWAY, "Server error");
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
