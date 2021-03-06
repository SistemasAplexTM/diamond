<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TSA - {{ $master}}</title>
    <style>
        #mvcIcon,
        #mvcMain {
            display: none;
        }

        * {
            /*font-size: 14px;*/
            font-family: sans-serif;
        }

        #imgLogo {
            width: 250;
            /*height: 120px;*/
            margin-top: 20px;
        }

        #center {
            text-align: center;
        }

        #centerTitle {
            text-align: center;
            font-size: 15px;
        }

        #centerStatement {
            text-align: center;
            font-size: 20px;
            padding: 30px;
        }

        #centerDetail {
            text-align: justify;
            padding: 30px;
        }

        #foot {

            padding-left: 40px;
            padding-bottom: 20px;
        }

        #awb {
            font-size: 20px;
        }

        #strong {
            font-weight: bold;
        }

        .agencia {
            text-align: center;
        }
    </style>
</head>

<body>

    <table width="700px;" border="" cellspacing="0" cellpadding="0" id="tableContainer">
        <tr>
            <td>
                <div class="list-group-item-text agencia">{{ $data->nombre }}</div>
                <div class="list-group-item-text agencia">{{ $data->direccion }}</div>
                <div class="list-group-item-text agencia">{{ $data->ciudad }} - {{ $data->pais }}</div>
                <div class="list-group-item-text agencia">{{ $data->telefono }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="center" style="width:300px;margin: 0 auto;">
                    @if($data->logo != '')
                    @if(env('APP_DEPELOPER'))
                    <img alt="image" class="" id="imgLogo"
                        src="{{ public_path() . '/storage/' }}/{{ ((isset($data->logo) and $data->logo != '') ? $data->logo : '') }}" />
                    @else
                    <img alt="image" class="" id="imgLogo"
                        src="{{ asset('storage/') }}/{{ ((isset($data->logo) and $data->logo != '') ? $data->logo : '') }}" />
                    @endif
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="centerStatement"><strong>IAC CARGO CERTIFICATION STATEMENT</strong></div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="centerDetail">
                    <strong id="strong">{{ $data->nombre }}</strong> is in compliance with TSA-approved security program
                    and
                    all applicable security directives.
                    Our number is SO0304002. This shipment contains cargo originating from an unknown shipper not
                    exempted
                    by TSA.
                    This shipment must be transported ONLY on <strong id="strong">ALL- CARGO AIRCRAFT ONLY.</strong>
                    The individual whose name appears below certifies that he or she is an employee or authorize
                    representative of
                    <strong id="strong">{{ $data->nombre }}</strong> and understand that any fraudulent or false
                    statement
                    made in connection with this certification may
                    subject this individual and <strong id="strong">{{ $data->nombre }}</strong> to both civil penalties
                    under 49 CFR Part 1540.103(b) and fines and/or
                    imprisonment of not more that 5 years under 18 U.S.C 1001.
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="foot" style="padding-top: 30px;">Master Airwaybill: <strong id="awb">{{ $master }}</strong>
                </div>
                <div id="foot" style="padding-top: 30px;">{{ $data->nombre }}</div>
                <div id="foot" style="padding-bottom: 50px;">Shipper Company</div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="foot" style="padding-top: 20px;">Items under 16 oz. ______________0______________</div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="foot">
                    Shipper's Signature.:____________________ &nbsp;&nbsp;&nbsp;
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="foot">
                    <table border="" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td>

                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Shipper's Name
                            </td>
                            <td>
                                Driver's Name
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="foot" style="font-size: 12px;">
                    SENSITIVE SECURITY INFORMATION
                    Warning: This record contains sensitive security information that is controlled under 49cfr parts 15
                    and
                    1520.
                    No part of this record may be disclosed to person without a “need to know” as defined in 49 CFR
                    parts 15
                    and 1520,
                    except with the written permission of the administrator of the transportation security
                    administration of
                    the secretary of
                    transportation. Unauthorized release may result in civil penalty or other action. For U.S.
                    Government
                    agencies, public
                    disclosure government by 5 U.S.C and 49 CFR parts 15 and 1520.
                </div>
            </td>
        </tr>
    </table>

</body>

</html>