<?php

namespace App\Policies\Admin;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotingBallotPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        return true;

        if (is_null($user)) {
            //abort(403, 'User needs to be logged in');
        }

        //return false;

        //return Bouncer::can('manage-voting');
    }

    public function manageVotingBallots()
    {
        return true;
        //return false;
    }
}
