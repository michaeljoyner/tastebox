<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgeDeploymentHookRequest;
use App\Notifications\ForgeDeployment;
use App\Notifications\SlackNotifiable;

class ForgeDeploymentsController extends Controller
{
    public function store(ForgeDeploymentHookRequest $request)
    {
        (new SlackNotifiable)->notify(new ForgeDeployment(
            success: $request->successful(),
            siteName: $request->site(),
            repoUrl: $request->repoLink(),
        ));
    }
}
