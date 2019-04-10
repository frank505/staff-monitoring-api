<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlHttpHelperController extends Controller
{
    //
    //
    
    // This method will perform an action/method thru HTTP/API calls
// Parameter description:
// Method= POST, PUT, GET etc
// Data= array("param" => "value") ==> index.php?param=value
public static function perform_http_request($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'content-type: application/json',
        'Authorization: key=AAAA1GdJKDE:APA91bH2z3HqSLKfNbw3Jm4dxOFADgT9G1DFTuyNtZ5zWLozcd7z6m9VXFliKmGTP62vVSoh-VtxJlEcIfi7Ho1HHHSrVVLGsTvRqueZCjmYG40b67YS6HF6ljHbf152j67BVHpV0UPI'
    ));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

    //end of this class

}
