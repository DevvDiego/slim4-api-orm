<?php
namespace App\Auth;

class Auth{
    private static ?object $user = null;

    /**
     * Set an authed user for the current session
     */
    public static function setUser(?object $user): void{
        self::$user = $user;
        
    }

    /**
     * Returns current authed user or null
     */
    public static function user(): ?object{
        return self::$user;
        
    }

    /**
     * Verifies if there is an authed user
     */
    public static function check(): bool{
        return self::$user !== null;
        
    }

    public static function id(): ?int{
        return self::$user->sub ?? null;
        
    }


    public static function email(): ?string{
        return self::$user->email ?? null;
        
    }
}