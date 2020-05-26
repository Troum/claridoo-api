<?php

namespace App\Http\Controllers;

use App\Http\Requests\IBAN;
use App\Http\Requests\Postcode;
use App\Http\Requests\Pricing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class APIController extends Controller
{
    /**
     * @param Postcode $request
     * @return bool|JsonResponse
     */
    public function postcode(Postcode $request)
    {
        if (strlen($request->postcode) >= 5 ) {
            $response = Http::get(env('APP_URL') . '/plz/prod/', [
                "PLZ" => $request->postcode
            ]);
            if ($response->successful()) {
                return response()->json(['city' => $response->json()], Response::HTTP_OK);
            } else {
                switch ($response->status()) {
                    case 400:
                        return response()->json(['error' =>'Cannot parse request'], Response::HTTP_BAD_REQUEST);
                    case 404:
                        return response()->json(['error' =>'City with this postcode was not found'], Response::HTTP_NOT_FOUND);
                }
            }


        }
        return false;
    }

    /**
     * @param Pricing $request
     * @return bool|JsonResponse
     */
    public function pricing(Pricing $request)
    {
        if (strlen($request->quantity) >= 4) {
            $response = Http::get(env('APP_URL') . '/pricing/prod/', [
                "PLZ" => $request->postcode,
                "Quantity" => $request->quantity
            ]);
            if ($response->successful()) {
                return response()->json(['info' => $response->json()], Response::HTTP_OK);
            }
            switch ($response->status()) {
                case 400:
                    return response()->json(['error' =>'Vielen Dank! Der von Ihnen gewünschte Tarif ist für den von
            Ihnen angegebenen Stromverbrauch nicht verfügbar. Für weitere Informationen bzw. zur
            Erstellung eines individuellen Angebotes steht Ihnen unser Kundenservice gerne per alpiq-energie@alpiq.com oder 030 4036 74010 zur Verfügung.'], Response::HTTP_BAD_REQUEST);
                case 404:
                    return response()->json(['error' =>'City with this postcode was not found'], Response::HTTP_NOT_FOUND);
            }
        }
        return false;

    }

    /**
     * @param IBAN $request
     * @return bool|JsonResponse
     */
    public function iban(IBAN $request)
    {
        if (strlen($request->iban) >= 16 ) {
            $response = Http::get(env('APP_URL') . '/iban/prod/', [
                "IBAN" => $request->iban
            ]);
            if ($response->successful()) {
                return response()->json(['info' => $response->json()], Response::HTTP_OK);
            }
            switch ($response->status()) {
                case 400:
                    return response()->json(['message' =>'Die Anfrage kann nicht analysiert werden.'], Response::HTTP_BAD_REQUEST);
                case 404:
                    return response()->json(['message' =>'IBAN ist nicht gültig'], Response::HTTP_NOT_FOUND);
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function phone(Request $request)
    {
        if (strlen($request->phone) >= 13 ) {
            $response = Http::get(env('APP_URL') . '/mobile/prod/', [
                "mobile" => $request->phone
            ]);
            if ($response->successful()) {
                return response()->json(['info' => $response->json()], Response::HTTP_OK);
            }
            switch ($response->status()) {
                case 400:
                    return response()->json(['message' =>'Cannot parse the request'], Response::HTTP_BAD_REQUEST);
                case 404:
                    return response()->json(['message' =>'Keine gültige deutsche Mobilfunknummer'], Response::HTTP_NOT_FOUND);
            }
        }

        return false;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function stepOne(Request $request)
    {
        $response = Http::post(env('APP_URL') . '/signup/prod/', $request->all());
        if ($response->successful()) {
            return response()->json(['session' => $response->json()], Response::HTTP_OK);
        }
        switch ($response->status()) {
            case 502:
                return response()->json(['error' =>'Die Anfrage kann nicht analysiert werden.'], Response::HTTP_BAD_REQUEST);
            case 404:
                return response()->json(['error' =>'IBAN ist nicht gültig'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function supplier(Request $request)
    {
        if (strlen($request->supplier) >= 2 ) {
            $response = Http::get(env('APP_URL') . '/ep/prod/', [
                "filter" => $request->supplier
            ]);
            if ($response->successful()) {
                return response()->json(['supplier' => $response->json()], Response::HTTP_OK);
            }
            switch ($response->status()) {
                case 400:
                    return response()->json(['message' =>'Cannot parse the request'], Response::HTTP_BAD_REQUEST);
                case 404:
                    return response()->json(['message' =>'Bisheriger Lieferant kann nicht gefunden werden'], Response::HTTP_NOT_FOUND);
            }
        }

        return false;
    }
}
