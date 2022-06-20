<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ErrorTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class MemberController extends Controller
{
    use ErrorTrait;

    /**
     * @OA\Post(
     * path="/api/auth/member/register",
     * operationId="Register",
     * tags={"Member Auth"},
     * summary="쇼핑몰 회원 가입",
     * description="쇼핑몰 회원 가입 API",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "password_confirmation"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $data = $request->all();

        try {
            $data['password'] = Hash::make($data['password']);
            $user             = User::create($data);
            $success['token'] = $user->createToken('authToken')->accessToken;
            $success['name']  = $user->name;
        } catch (\Exception $exception) {
            $this->apiExceptionProc(__METHOD__ . '::Error::' . json_encode($exception), __METHOD__ . '::Error::' . json_encode($exception->getPrevious()));
            return response()->json($exception->getPrevious(), 422);
        }

        return response()->json(['success' => $success], 200);
    }
}
