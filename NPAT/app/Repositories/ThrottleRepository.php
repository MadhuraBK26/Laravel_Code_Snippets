<?php
namespace App\Repositories;

use App\Models\Throttle;

class ThrottleRepository
{
    /*
     * Method will check the throttle already created else it will update the
     * existing throttle else if hit  count is reached means
     * it will block the access
     */

    public function createOrUpdate($identifier, $value, $limit, $time)
    {
        // check the record is already created
        $throttle = Throttle::where('identifier', '=', $identifier)
            ->where('value', '=', $value)
            ->where('route_url', '=', \Request::url())
            ->where('method', '=', \Request::method());
        if (config('throttle.includeIp')) {
            $throttle = $throttle->where('ip', '=', \Request::ip());
        }
        $throttle = $throttle->first();

        // [after first time]now throttle variable have some value not null , so it will failed on if part itself

        if ($throttle) {

            if ($throttle->expires_at) {
                $strStart = strtotime($throttle->expires_at);
                $strEnd = strtotime('now');
                $dteDiff = $strEnd - $strStart;
                if ($dteDiff > $time) {
                    $throttle->delete();
                    return false;
                } else {
                    $throttle->expires_at = date('Y-m-d H:i:s');
                    $throttle->save();
                    return true;
                }
            }
            if ($throttle->hits >= $limit) {
                $throttle->expires_at = date('Y-m-d H:i:s');
                $throttle->save();
                return true;
            } else {
                $throttle->hits = $throttle->hits + 1;
                $throttle->save();
                return false;
            }

        } else {
            // if no record this scope will call
            // No record means we have to insert it
            $throttle = new Throttle();
            $throttle->identifier = $identifier;
            $throttle->hits = 1;
            $throttle->value = $value;
            if (config('throttle.includeIp')) {
                $throttle->ip = \Request::ip();
            }
            $throttle->route_url = \Request::url();
            $throttle->method = \Request::method();
            $throttle->save();
            return false;
        }
    }
}
