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

    $xml = $array['S:Body']['ns2:genelSorguResponse']['return'];
   
    $xml = simplexml_load_string($xml);
    $json = json_encode($xml);
    $data = json_decode($json, true);
    
    $list = $data['PetrolPiyasasiEnYuksekHacimliSekizFirmaninAkaryakitFiyatlari'];

    foreach($list as $item) {
        echo "<ul><li>Yakıt Tipi: " . $item['YakitTipi'] . "</li><li>Birim: " . $item['Birim'] . "</li><li>Fiyat: " . $item['Fiyat'] . "</li></ul>";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}

?>
