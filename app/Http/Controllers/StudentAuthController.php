<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            $token = $student->createToken('authToken')->plainTextToken;

            return response()->json([
                'student_id' => $student->id,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => 'student',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $token = $student->currentAccessToken();

        if ($token) {
            $token->delete();
            return response()->json(['message' => 'Logged out']);
        }

        return response()->json(['message' => 'Token not found'], 400);
    }
}
