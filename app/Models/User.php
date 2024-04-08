<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userVerify()
    {
        return $this->hasOne(UserVerify::class);
    }

    //user_identifications
    public function userIdentifications()
    {
        return $this->hasOne(UserIdentification::class);
    }

    //user_phone_references
    public function userPhoneReferences()
    {
        return $this->hasOne(UserPhoneReference::class);
    }

    //user_finances
    public function userFinances()
    {
        return $this->hasOne(UserFinance::class);
    }

    //user_salary_statements
    public function userSalaryStatements()
    {
        return $this->hasOne(UserSalaryStatement::class);
    }

    //user_phone_work_places
    public function userPhoneWorkPlaces()
    {
        return $this->hasOne(UserPhoneWorkPlace::class);
    }

    //user_licenses
    public function userLicenses()
    {
        return $this->hasOne(UserLicense::class);
    }

    //user_san_estates
    public function userSanEstates()
    {
        return $this->hasOne(UserSanEstate::class);
    }

    //user_movables
    public function userMovables()
    {
        return $this->hasOne(UserMovables::class);
    }

    //user_loan_amounts
    public function userLoanAmounts()
    {
        return $this->hasOne(UserLoanAmount::class);
    }
}
