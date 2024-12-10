<?

namespace App\Utils\Support;

use Illuminate\Support\Facades\Http;

class APIDiginet
{
    public static function getDatasourceFromAPI($name, $data = [], $X_AccessToken = '',)
    {
        $X_AccessToken = $X_AccessToken ? $X_AccessToken : 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.9C4Wv1O56qRbC-fOvFbqZWDjhdvKz6D4kc-e9nZ04Co';
        $API_Host =  "http://192.168.100.23/hr-api-dev/v1.0.0/D29A2020/";
        $url = $API_Host . $name;
        $data = empty($data) ? [
            "FromDate" => "2023-11-01",
            "ToDate" => "2023-11-30",
            "CompanyCode" => "TLCM,TLCE",
            "WorkplaceCode" => "HO,TF1,TF2,TF3,NZ,1HO,1TF1,1TF2,1TF3,1NZ"
        ] : $data;
        $token = CurrentUser::getTokenForApi();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'X-Access-Token' => $X_AccessToken,
        ])->post($url, $data);
        if ($response->successful()) return $response->json();
        return response()->json(['error' => "Unable to fetch data of '{$name}'"]);
    }
}
