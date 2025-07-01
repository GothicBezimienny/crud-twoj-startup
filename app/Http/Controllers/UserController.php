<?php

namespace App\Http\Controllers;

use App\Application\Services\EmailService;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserRequest;

class UserController
{
    public function __construct(public EmailService $emailService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return User::with('emails')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());
        } catch (\Throwable) {
            Log::error('Failed to create User');
            return response()->json(status: RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($user, RESPONSE::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): User
    {
        return $user->load('emails');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
        } catch (\Throwable) {
            Log::error('Failed to delete user');

            return response()->json(status: RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function sendWelcomeEmail(User $user): JsonResponse
    {
        try {
            $this->emailService->sendWelcomeEmail($user);
        } catch (\Throwable) {
            Log::error('Email not sent');

            return response()->json(status: RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
