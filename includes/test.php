<?php

wcip_xml_post();

/**
 * Sends API data to India Post.
 *
 * @param array $data shipment data.
 */
function wcip_xml_post( $data = array() ) {

	$xml_data = array();

	$manifest_data = array();

	$consignee_address                = array();
	$consignee_address['name']        = 'name';
	$consignee_address['address1']    = 'address1';
	$consignee_address['address2']    = 'address2';
	$consignee_address['address3']    = 'address3';
	$consignee_address['city']        = 'Bangalore';
	$consignee_address['pincode']     = '560085';
	$consignee_address['CountryCode'] = '+91';
	$consignee_address['MobileNo']    = '8762657259';

	$shipment_package_info                            = array();
	$shipment_package_info['articleNumber']           = '14A';
	$shipment_package_info['referenceNumber']         = '45545454';
	$shipment_package_info['ShipmentMethodOfPayment'] = 'Cash';

	$cash_on_delivery_charge                      = array();
	$cash_on_delivery_charge['chargeOrAllowance'] = '30';
	$cash_on_delivery_charge['monetaryAmount']    = '12';

	$actual_gross_wieght                = array();
	$actual_gross_wieght['weightValue'] = '2';

	$shipment_package_info['CashOnDeliveryCharge']             = $cash_on_delivery_charge;
	$shipment_package_info['shipmentPackageActualGrossWeight'] = $actual_gross_wieght;
	$shipment_package_info['insuredValue']                     = '1';
	$shipment_package_info['ProofOfDelivery']                  = 'OTP';

	$xml_data['manifestDetail']                        = array();
	$xml_data['manifestDetail']['consigneeAddress']    = $consignee_address;
	$xml_data['manifestDetail']['shipmentPackageInfo'] = $shipment_package_info;

	$xml = get_xml_from_array( $xml_data );

	var_dump( $xml );

}

function get_xml_from_array( $xml_data ) {
	$dom                = new DOMDocument( '1.0', 'UTF-8' );
	list( $dom, $node ) = xml_recursion( $xml_data, $dom );

	return $dom->saveXML();
}

/**
 * Get xml from array.
 *
 * @param array  $xml_data .
 * @param object $dom .
 * @param object $node .
 */
function xml_recursion( $xml_data, $dom, $node = null ) {
	if ( is_array( $xml_data ) ) {
		foreach ( $xml_data as $xml_data_key => $xml_data_value ) {
			if ( is_array( $xml_data_value ) ) {
				$element = $dom->createElement( $xml_data_key );
				if ( null === $node ) {
					 $node = $dom->appendChild( $element );
				} else {
					$node->appendChild( $element );
				}
				list( $dom, $node ) = xml_recursion( $xml_data_value, $dom, $node );
			} else {
				$element = $dom->createElement( $xml_data_key, $xml_data_value );
				if ( null === $node ) {
					$node = $dom->appendChild( $element );
				} else {
					$node->appendChild( $element );
				}
			}
		}
	}
	return array( $dom, $node );
}