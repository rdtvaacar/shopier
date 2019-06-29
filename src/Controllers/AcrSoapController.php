<?php

namespace Acr\Ftr\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Acr\Ftr\Controllers\Fit\Entity\UserListsRequest;

class AcrSoapController
{
    /**
     * @var SoapWrapper
     */
    protected $soapWrapper;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapWrapper
     */
    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }

    /**
     * Use the SoapWrapper
     */
    public function show()
    {
        $this->soapWrapper->add('Currency', function ($service) {
            $service
                ->wsdl('https://earsivwstest.fitbulut.com/ClientEArsivServicesPort.svc')// The WSDL url
                ->trace(true)// Optional: (parameter: true/false)
                /*->customheader()// optional: (parameters: $customerheader) use this to add a custom soapheader or extended class
                ->cookie()// optional: (parameters: $name,$value)
                ->location()// optional: (parameter: $location)
                ->certificate()// optional: (parameter: $certlocation)*/
                //->cache(WSDL_CACHE_NONE)// Optional: Set the WSDL cache

                // Optional: Set some extra options
                ->options([
                    'login'    => 'Hb2iphtC',
                    'password' => 'HC%GKmP4'
                ])
                // Optional: Classmap
                ->classmap([
                    UserListsRequest::class,
                ]);
        });
        // Without classmap
        $response = $this->soapWrapper->call('Currency.UserListsRequest', [
            new UserListsRequest('getXML')
        ]);
        dd($response);
        // With classmap
        exit;
    }

}

/*$this->soapWrapper->add('Currency', function ($service) {
    $service
        ->wsdl()                 // The WSDL url
        ->trace(true)            // Optional: (parameter: true/false)
        ->header()               // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
        ->customHeader()         // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class
        ->cookie()               // Optional: (parameters: $name,$value)
        ->location()             // Optional: (parameter: $location)
        ->certificate()          // Optional: (parameter: $certLocation)
        ->cache(WSDL_CACHE_NONE) // Optional: Set the WSDL cache

        // Optional: Set some extra options
        ->options([
            'login' => 'username',
            'password' => 'password'
        ])

        // Optional: Classmap
        ->classmap([
            GetConversionAmount::class,
            GetConversionAmountResponse::class,
        ]);
});*/