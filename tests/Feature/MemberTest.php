<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class MemberTest extends TestCase
{
    /**
     * 회원 가입 Test
     *
     * @return void
     */
    public function test_member_register()
    {
        User::where('email', 'test@naver.com')->delete();

        $response = $this->post('/api/auth/member/register', [
            'name'                  => 'test',
            'email'                 => 'test@naver.com',
            'password'              => 'test12',
            'password_confirmation' => 'test12',
            'phone'                 => '01000000000',
        ]);

        $response->assertStatus(200);
    }
}
