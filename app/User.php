<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'email', 'approved', 'role', 'password', 'mobilenumber', 'currentclient_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isSuperAdmin()
    {
        return $this->role == "admin";
    }

    public function isAdmin()
    {
        return $this->isAdminOfClient(Auth::user()->currentclient_id);
    }

    public function isAdminOfClient($clientID)
    {
        if (Client_user::where(['client_id' => $clientID, 'user_id' => Auth::user()->id, 'isAdmin' => true])->count() > 0)
        {
            return true;
        }
        return false;
    }

    public function isTrainingEditor()
    {
        if (Client_user::where(['client_id' => Auth::user()->currentclient_id, 'user_id' => Auth::user()->id, 'isTrainingEditor' => true])->count() > 0)
        {
            return true;
        }
        return false;
    }

    public function isTrainingEditorOfClient($clientID)
    {
        if (Client_user::where(['client_id' => $clientID, 'user_id' => Auth::user()->id, 'isTrainingEditor' => true])->count() > 0)
        {
            return true;
        }
        return false;
    }

    public function qualifications()
    {
        return $this->belongsToMany(Qualification::class, 'qualification_users')->where('client_id', '=', Auth::user()->currentclient_id)->orderBy('name');
    }

    public function qualificationsNotAssignedToUser()
    {
        $id = $this->id;
        return Qualification::leftJoin('qualification_users',function ($join) use ($id) {
            $join->on('qualifications.id', '=', 'qualification_users.qualification_id');
            $join->on('qualification_users.user_id', '=', DB::raw("'". $id. "'"));
        })->whereNull('qualification_users.qualification_id')->select('qualifications.*')
            ->where('qualifications.client_id', '=', Auth::user()->currentclient_id)->orderBy('qualifications.name');
    }

    public function hasqualification($qualificationid)
    {
        if($this->belongsToMany(Qualification::class, 'qualification_users')->where('qualification_id', '=', $qualificationid)->count() > 0){
            return true;
        }
        return false;
    }

    public function hascandidate($positionid)
    {
        if($this->hasMany(PositionCandidature::class)->where('positioncandidatures.position_id', '=', $positionid)->count() > 0){
            return true;
        }
        return false;
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
    
    public function authorizedpositions()
    {
        return $this->hasMany(Position::class)->join('services', 'positions.service_id', '=', 'services.id')
            ->where('services.client_id', '=', Auth::user()->currentclient_id)
            ->orderBy('services.date')->with('qualification');
    }

    public function authorizedpositions_future()
    {
        return $this->authorizedpositions()->where('services.date','>=', DB::raw('CURDATE()'));
    }

    public function clients()
    {
        return $this->hasManyThrough(
            Client::class,
            Client_user::class,
            'user_id', // Foreign key on Client_user table...
            'id', // Foreign key on clients table...
            'id', // Local key on users table...
            'client_id' // Local key on Client_user table...
        );
    }

    public function currentclient()
    {
        return $this->clients()->where('clients.id', '=', Auth::user()->currentclient_id)->first();
    }

    public function clients_candidature()
    {
        return $this->hasManyThrough(
            Client::class,
            Client_user::class,
            'user_id', // Foreign key on Client_user table...
            'id', // Foreign key on clients table...
            'id', // Local key on users table...
            'client_id' // Local key on Client_user table...
        )->where(['client_user.approved' => false]);
    }
}
