<?php

function convertState($state, $full_name = false){
        $states = array(
                        'AL' => 'Alabama',
                        'AK' => 'Alaska',
                        'AZ' => 'Arizona',
                        'AR' => 'Arkansas',
                        'CA' => 'California',
                        'CO' => 'Colorado',
                        'CT' => 'Connecticut',
                        'DE' => 'Delaware',
                        'FL' => 'Florida',
                        'GA' => 'Georgia',
                        'HI' => 'Hawaii',
                        'ID' => 'Idaho',
                        'IL' => 'Illinois',
                        'IN' => 'Indiana',
                        'IA' => 'Iowa',
                        'KS' => 'Kansas',
                        'KY' => 'Kentucky',
                        'LA' => 'Louisiana',
                        'ME' => 'Maine',
                        'MD' => 'Maryland',
                        'MA' => 'Massachusetts',
                        'MI' => 'Michigan',
                        'MN' => 'Minnesota',
                        'MS' => 'Mississippi',
                        'MO' => 'Missouri',
                        'MT' => 'Montana',
                        'NE' => 'Nebraska',
                        'NV' => 'Nevada',
                        'NH' => 'New Hampshire',
                        'NJ' => 'New Jersey',
                        'NM' => 'New Mexico',
                        'NY' => 'New York',
                        'NC' => 'North Carolina',
                        'ND' => 'North Dakota',
                        'OH' => 'Ohio',
                        'OK' => 'Oklahoma',
                        'OR' => 'Oregon',
                        'PA' => 'Pennsylvania',
                        'RI' => 'Rhode Island',
                        'SC' => 'South Carolina',
                        'SD' => 'South Dakota',
                        'TN' => 'Tennessee',
                        'TX' => 'Texas',
                        'UT' => 'Utah',
                        'VT' => 'Vermont',
                        'VA' => 'Virginia',
                        'WA' => 'Washington',
                        'WV' => 'West Virginia',
                        'WI' => 'Wisconsin',
                        'WY' => 'Wyoming',
                        'DC' => 'District of Columbia',
                        'AS' => 'American Samoa',
                        'GU' => 'Guam',
                        'MP' => 'Northern Mariana Islands',
                        'PR' => 'Puerto Rico',
                        'UM' => 'United States Minor Outlying Islands',
                        'VI' => 'Virgin Islands, U.S.',
                        'AB' => 'Alberta',
                        'BC' => 'British Columbia',
                        'MB' => 'Manitoba',
                        'NB' => 'New Brunswick',
                        'NL' => 'Newfoundland and Labrador',
                        'NS' => 'Nova Scotia',
                        'ON' => 'Ontario',
                        'PE' => 'Prince Edward Island',
                        'QC' => 'Quebec',
                        'SK' => 'Saskatchewan',
                        'NT' => 'Northwest',
                        'NU' => 'Nunavut',
                        'YT' => 'Yukon Territory',);
        if($full_name){
                $states = array_flip($states);
        }
        return array_key_exists($state, $states)?$states[$state]:$state;
}       
include_once('geoPHP/geoPHP.inc');
$file=file_get_contents("states_json.txt");
$json = json_decode($file,true);

//var_dump($json['features']);

foreach ( $json['features'] as $customer) 
{
    #var_dump($customer);
    $geom = geoPHP::load(json_encode($customer),'json');
      print( "INSERT INTO `location` (`state_id`, `location_code`, `location_name`, `geometry`) VALUES (NULL,'US-".convertState($customer['properties']['name'], true)."' ,'".$customer['properties']['name']."',GeomFromText('".$geom->out('wkt')."'));\n");
  


}

?>
