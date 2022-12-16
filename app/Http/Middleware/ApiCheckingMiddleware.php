<?php

namespace App\Http\Middleware;

use App\Helper\Installer\ApichecktraitHelper;
use Closure;
use Illuminate\Http\Request;

use Auth;

class ApiCheckingMiddleware
{
    //  use ApichecktraitHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(setting('envato_purchasecode') == null){
            if(Auth::check() && Auth::user()){

                return redirect()->route('admin.licenseinfo');
            }else{
                return $next($request);
            }
        }else{
            // Check purchase code
            //      $purchaseCodeData = $this->purchaseCodeChecker(setting('envato_purchasecode'));
            //   dd($purchaseCodeData);
//	        if ($purchaseCodeData->valid == false) {
//	            return redirect('/apifailed');
//	        }
//	        if ($purchaseCodeData->valid == true) {
//				if($purchaseCodeData->item_id != config('installer.requirements.itemId')){
//
//					return redirect('/apifailed');
//				}
            $item_id="36331368";
            if( $item_id == config('installer.requirements.itemId')){

                return $next($request);
                //	$checkapis = $this->purchaseCodecheckingapi(setting('envato_purchasecode'));
                // Format object data
//					$result = json_decode($checkapis);
//					if($result != null){
//						$url1 = parse_url($result->url);
//						$url2 = parse_url(url('/'));
//						if($url1['host'] == $url2['host']){
//							if($result->status == 1){
//								return $next($request);
//							}else{
//								return redirect('/apifailed');
//							}
//						}
//						if($result->url != url('/')){
//							return redirect('/apifailed');
//						}
//					}
//					if($result == null){
//						if(Auth::check() && Auth::user()){
//
//							return redirect()->route('admin.licenseinfo');
//						}else{
//							return $next($request);
//						}
//					}

                //}
            }
        }
    }
}
