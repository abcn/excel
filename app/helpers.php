<?php

/**
 * Global helpers file with misc functions
 *
 */

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('javascript')) {
    /**
     * Access the javascript helper
     */
    function javascript()
    {
        return app('JavaScript');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('getFallbackLocale')) {
    /**
     * Get the fallback locale
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function getFallbackLocale()
    {
        return config('app.fallback_locale');
    }
}

if (! function_exists('getLanguageBlock')) {

    /**
     * Get the language block with a fallback
     *
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getLanguageBlock($view, $data = [])
    {
        $components = explode("lang", $view);
        $current  = $components[0]."lang.".app()->getLocale().".".$components[1];
        $fallback  = $components[0]."lang.".getFallbackLocale().".".$components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }
}
//上传excel
if (! function_exists('uploadExcel')) {
    function uploadExcel($file,$allow = ["xls", "xlsx", "csv"])
    {
        if ($file) {
            $allowed_extensions = $allow;
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return ['error' => 'You may only upload'.$allow];
            }
            $fileName        = $file->getClientOriginalName();
            $extension       = $file->getClientOriginalExtension() ?: $allow[0];
            $folderName      = 'uploads/Excel/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;
            $destinationPath = public_path() . '/' . $folderName;
            $safeName        = str_random(10).'.'.$extension;
            $file->move($destinationPath, $safeName);
            $data['success'] = true;
            $data['filename'] = $folderName .'/'. $safeName;
        } else {
            $data['success'] = false;
            $data['error'] = 'Error while uploading file';
        }
        return $data;
    }
}
//上传身份证
if (! function_exists('uploadID')) {
    function uploadID($file,$allow = ["png", "jpg", "gif"])
    {
        if ($file) {
            $allowed_extensions = $allow;
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return ['error' => 'You may only upload'];
            }
            $fileName        = $file->getClientOriginalName();
            $extension       = $file->getClientOriginalExtension() ?: $allow[0];
            $folderName      = 'uploads/ID/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;
            $destinationPath = public_path() . '/' . $folderName;
            $safeName        = str_random(10).'.'.$extension;
            $file->move($destinationPath, $safeName);
            $data['success'] = true;
            $data['filename'] = $folderName .'/'. $safeName;
        } else {
            $data['success'] = false;
            $data['error'] = 'Error while uploading file';
        }
        return $data;
    }
}

function getUserStaticDomain()
{
    return Config::get('app.user_static') ?: Config::get('app.url');
}
//上传图片至api
if (! function_exists('uploadImage')) {
    /**
     *
     */
    function uploadImage($fileName,$url)
    {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => env('API_URL'),
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $response = $client->request('POST', $url, [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen(storage_path() . '/' . $fileName, 'r')
                ],
            ]
        ]);
        $response = json_decode($response->getBody());
        return $response->message;
    }
}

if (! function_exists('bower')){
    function bower($path)
    {
        return asset('bower_components/'.$path);
    }
}