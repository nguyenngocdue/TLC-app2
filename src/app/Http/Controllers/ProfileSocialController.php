<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileSocialController extends Controller
{
    protected function getEditTopTitle()
    {
        return "Profile";
    }
    public function getType()
	{
		return 'profile_social';
	}
    public function profileSocial(Request $request, $id){
        return  view('dashboards.pages.profile',
            [
                'topTitle' => $this->getEditTopTitle(),
            ]);
    }
}
