<?php
namespace App\Repositories;

use DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginRepository
 {
    public function athenticate( $request )
 {
        $credentials = $request->only( 'email', 'password' );
        try {
            $token = JWTAuth::attempt( $credentials );
            if ( ! $token) {
                return $token;
            }
        } catch ( JWTException $e ) {
            return response()->json( [
                'token' => $token,
                'success' => false,
                'message' => 'Could not create token.',
            ], 500 );
        }
        return $token;
    }
}