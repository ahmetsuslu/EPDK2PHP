<?php

try {
    $options = array(
        'location' => "https://lisansws.epdk.gov.tr/services/bildirimPetrol8FirmaBulten.bildirimPetrol8FirmaBultenHttpSoap11Endpoint" ,
        'soap_version'=>SOAP_1_1,
        'exceptions'=>true,
        'trace'=>1,
    );

    $today = date("d/m/Y");

    $query = array(array('sorguNo'=>'71', 'parametreler' => $today));

    $wsdl = 'https://lisansws.epdk.gov.tr/services/bildirimPetrol8FirmaBulten?wsdl';

    $client = new SoapClient($wsdl, $options);

    $client->__soapCall('genelsorgu', $query);

    $response = $client->__getLastResponse();

    $array = xmlrpc_parse_method_descriptions($response);

    $xml_raw = "";
    foreach($array as $array_) {
        foreach ($array_ as $_array) {
            foreach ($_array as $_array_) {
                $xml_raw = $_array_;
            }
        }
    }

    $xml = simplexml_load_string($xml_raw);
    $json = json_encode($xml);
    $data = json_decode($json);
    $list = $data->PetrolPiyasasiEnYuksekHacimliSekizFirmaninAkaryakitFiyatlari;

    for ($i = 0; $i < count($list); $i++) {
        echo "<ul><li>Yakıt Tipi: ".$list[$i]->YakitTipi."</li><li>Birim: " . $list[$i]->Birim ."</li><li>Fiyat: ". $list[$i]->Fiyat. "</li></ul>";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}

?>