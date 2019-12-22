<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SocialCampaign;
use App\Models\SocialCampaignClick;

class SocialCampaignMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->get('utm_source') && $request->get('utm_medium') && $request->get('utm_campaign')) {
            $this->socialCampaign($request->get('utm_source'), $request->get('utm_medium'), $request->get('utm_campaign'));
        }
        return $next($request);
    }

    private function socialCampaign($utm_source, $utm_medium, $utm_campaign) {
        $SocailCampaign = SocialCampaign::where('utm_source', $utm_source)
                ->where('utm_medium', $utm_medium)
                ->where('utm_campaign', $utm_campaign)
                ->first();

        if (!$SocailCampaign) {
            $SocailCampaign = new SocialCampaign;
            $SocailCampaign->utm_source = $utm_source;
            $SocailCampaign->utm_medium = $utm_medium;
            $SocailCampaign->utm_campaign = $utm_campaign;
            $SocailCampaign->save();
        }

        $row = new SocialCampaignClick;
        $row->utm_source_id = $SocailCampaign->id;
        $row->save();
        setSocialID($row->id);
    }

}
