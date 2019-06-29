<?php

namespace Acr\Ftr\Controllers;

use Acr\Ftr\Controllers\Parasut\Client;
use Acr\Ftr\Model\Parasut_conf;
use Illuminate\Http\Request;

class ParasutController
{
    protected $token;
    public    $account_id;

    function __construct()
    {
        $parasut_model = new Parasut_conf();
        $parasut_conf  = $parasut_model->first();
        $parasut       = new Client([
            'client_id'     => $parasut_conf->client_id,
            'client_secret' => $parasut_conf->client_secret,
            'username'      => $parasut_conf->username,
            'password'      => $parasut_conf->password,
            'company_id'    => $parasut_conf->company_id,
            'grant_type'    => 'password',
            'redirect_uri'  => 'urn:ietf:wg:oauth:2.0:oob',
        ]);
        // dd();
        $parasut->authorize();
        $this->token      = $parasut;
        $this->account_id = self::account_id();
        /*   if ($parasut_conf->account_id != $parasut_conf->account_id) {
               $parasut_model->where('id', $parasut_conf->id)->update(['account_id' => $])
           }*/
    }

    function contact($data)
    {
        $contact = $this->token->make('contact')->create($data);
// the contact token value
        return $contact['contact']['id'];
    }

    function find($id)
    {
        $contact = $this->token->make('sale')->find($id);
// the contact token value
        return $contact;
    }

    function contact_update($id, $data)
    {
        $contact = $this->token->make('contact')->update($id, $data);
// the contact token value
        return $contact['contact']['id'];
    }

    function index()
    {
        $account = $this->token->make('account')->get();;

        dd($account['items'][0]['id']);
    }

    function account_id()
    {
        $account = $this->token->make('account')->get();;

        return $account['items'][0]['id'];
    }

    function sale($data)
    {
        $sale = $this->token->make('sale')->create($data);
        return (object)$sale['sales_invoice'];
    }

    function product($data)
    {
        $product = $this->token->make('product')->create($data);
        return $product['product']['id'];
    }

    function invoice($id)
    {
        return $this->token->make('sale')->find($id);
    }

    function e_arsiv($invoice_id, $data)
    {
        $e_arsiv = $this->token->make('sale')->createEArchive($invoice_id, $data);
        return $e_arsiv;
    }

    function purchase()
    {

        //  dd($this->contact());
        $purchase = $this->token->make('purchase')->get([
            'description'        => 'Büyük tedarikçi techizat alımı',
            'invoice_id'         => '1',
            'invoice_series'     => 'A',
            'item_type'          => 'invoice',
            'invoice_no'         => '',
            'issue_date'         => '2016-01-15',
            'contact_id'         => $this->contact(),
            'category_id'        => null,
            'archived'           => null,
            'details_attributes' => [
                [
                    'product_id'     => 3556381, // the parasut products
                    'quantity'       => 1,
                    'unit_price'     => 100,
                    'vat_rate'       => 18,
                    'discount_type'  => 'amount',
                    'discount_value' => 0,
                ],
            ],
        ]);


        return $purchase['purchase_invoice']['id'];
    }

    function paid($invoice_id, $data)
    {
        $this->token->make('sale')->paid($invoice_id, $data);
    }

    function sales_invoices()
    {
        return $this->token->make('sale')->get();

    }

    function sales_invoice_delete(Request $request)
    {
        return $this->token->make('sale')->delete($request->id);

    }
}